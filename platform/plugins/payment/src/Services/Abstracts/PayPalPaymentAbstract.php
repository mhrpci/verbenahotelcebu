<?php

namespace Botble\Payment\Services\Abstracts;

use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Support\Services\ProduceServiceInterface;
use Botble\Payment\Services\Traits\PaymentErrorTrait;
use Exception;
use Illuminate\Http\Request;
use PayPal\Exception\PayPalConfigurationException;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Exception\PayPalInvalidCredentialException;
use PayPal\Exception\PayPalMissingCredentialException;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;
use PayPal\Api\Payer;
use PayPal\Api\PaymentExecution;

abstract class PayPalPaymentAbstract implements ProduceServiceInterface
{
    use PaymentErrorTrait;

    /**
     * @var ApiContext
     */
    protected $apiContext;

    /**
     * @var Item
     */
    protected $itemList;

    /**
     * @var string
     */
    protected $paymentCurrency;

    /**
     * @var int
     */
    protected $totalAmount;

    /**
     * @var string
     */
    protected $returnUrl;

    /**
     * @var string
     */
    protected $cancelUrl;

    /**
     * PayPalPaymentAbstract constructor.
     */
    public function __construct()
    {
        $payPalMode = setting('payment_paypal_mode') ? 'live' : 'sandbox';

        config([
            'plugins.payment.payment.paypal.client_id'     => setting('payment_paypal_client_id'),
            'plugins.payment.payment.paypal.secret'        => setting('payment_paypal_client_secret'),
            'plugins.payment.payment.paypal.settings.mode' => $payPalMode,
        ]);

        $config = config('plugins.payment.payment.paypal');

        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                $config['client_id'],
                $config['secret']
            )
        );

        $this->apiContext->setConfig($config['settings']);

        $this->paymentCurrency = config('plugins.payment.payment.currency');

        $this->totalAmount = 0;
    }

    /**
     * Set payment currency
     *
     * @param string $currency String name of currency
     * @return self
     */
    public function setCurrency($currency)
    {
        $this->paymentCurrency = $currency;

        return $this;
    }

    /**
     * Get current payment currency
     *
     * @return string Current payment currency
     */
    public function getCurrency()
    {
        return $this->paymentCurrency;
    }

    /**
     * Add item to list
     *
     * @param array $itemData Array item data
     * @return self
     */
    public function setItem($itemData)
    {
        if (count($itemData) === count($itemData, COUNT_RECURSIVE)) {
            $itemData = [$itemData];
        }

        foreach ($itemData as $data) {
            $item = new Item;

            $item->setName($data['name'])
                ->setCurrency($this->paymentCurrency)
                ->setSku($data['sku'])
                ->setQuantity($data['quantity'])
                ->setPrice($data['price']);
            $this->itemList[] = $item;
            $this->totalAmount += $data['price'] * $data['quantity'];
        }

        return $this;
    }

    /**
     * Get list item
     *
     * @return Item List item
     */
    public function getItemList()
    {
        return $this->itemList;
    }

    /**
     * Get total amount
     *
     * @return mixed Total amount
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * Set return URL
     *
     * @param string $url Return URL for payment process complete
     * @return self
     */
    public function setReturnUrl($url)
    {
        $this->returnUrl = $url;

        return $this;
    }

    /**
     * Get return URL
     *
     * @return string Return URL
     */
    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    /**
     * Set cancel URL
     *
     * @param string $url Cancel URL for payment
     * @return self
     */
    public function setCancelUrl($url)
    {
        $this->cancelUrl = $url;

        return $this;
    }

    /**
     * Get cancel URL of payment
     *
     * @return string Cancel URL
     */
    public function getCancelUrl()
    {
        return $this->cancelUrl;
    }

    /**
     * Create payment
     *
     * @param string $transactionDescription Description for transaction
     * @return mixed PayPal checkout URL or false
     * @throws Exception
     */
    public function createPayment($transactionDescription)
    {
        $payer = new Payer;
        $payer->setPaymentMethod(PaymentMethodEnum::PAYPAL);

        $itemList = new ItemList;
        $itemList->setItems($this->itemList);

        $amount = new Amount;
        $amount->setCurrency($this->paymentCurrency)
            ->setTotal($this->totalAmount);

        // Transaction
        $transaction = new Transaction;
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($transactionDescription);

        $redirectUrls = new RedirectUrls;

        if (empty($this->cancelUrl)) {
            $this->cancelUrl = $this->returnUrl;
        }

        $redirectUrls->setReturnUrl($this->returnUrl)
            ->setCancelUrl($this->cancelUrl);

        $payment = new Payment;
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions([$transaction]);

        try {
            $payment->create($this->apiContext);
        } catch (PayPalConnectionException $exception) {
            $this->setErrorMessageAndLogging($exception, 1);
            return false;
        } catch (PayPalConfigurationException $exception) {
            $this->setErrorMessageAndLogging($exception, 2);
            return false;
        } catch (PayPalInvalidCredentialException $exception) {
            $this->setErrorMessageAndLogging($exception, 3);
            return false;
        } catch (PayPalMissingCredentialException $exception) {
            $this->setErrorMessageAndLogging($exception, 4);
            return false;
        } catch (Exception $exception) {
            $this->setErrorMessageAndLogging($exception, 5);
            return false;
        }

        $checkoutUrl = $payment->getApprovalLink();
        if (!empty($checkoutUrl)) {
            session(['paypal_payment_id' => $payment->getId()]);
        }

        return $checkoutUrl;
    }

    /**
     * Get payment status
     *
     * @param Request $request
     * @return mixed Object payment details or false
     */
    public function getPaymentStatus(Request $request)
    {
        if (empty($request->input('PayerID')) || empty($request->input('token'))) {
            return false;
        }

        $payment = Payment::get($request->input('paymentId'), $this->apiContext);

        $paymentExecution = new PaymentExecution();
        $paymentExecution->setPayerId($request->input('PayerID'));

        $paymentStatus = $payment->execute($paymentExecution, $this->apiContext);

        return $paymentStatus;
    }

    /**
     * Get payment list
     *
     * @param int $limit Limit number payment
     * @param int $offset Start index payment
     * @return mixed Object payment list
     * @throws Exception
     */
    public function getPaymentList($limit = 10, $offset = 0)
    {
        $params = [
            'count'       => $limit,
            'start_index' => $offset,
        ];

        try {
            $payments = Payment::all($params, $this->apiContext);
        } catch (PayPalConnectionException $exception) {
            $this->setErrorMessageAndLogging($exception, 1);
            return false;
        } catch (PayPalConfigurationException $exception) {
            $this->setErrorMessageAndLogging($exception, 2);
            return false;
        } catch (PayPalInvalidCredentialException $exception) {
            $this->setErrorMessageAndLogging($exception, 3);
            return false;
        } catch (PayPalMissingCredentialException $exception) {
            $this->setErrorMessageAndLogging($exception, 4);
            return false;
        } catch (Exception $exception) {
            $this->setErrorMessageAndLogging($exception, 5);
            return false;
        }

        return $payments;
    }

    /**
     * Get payment details
     *
     * @param string $paymentId PayPal payment Id
     * @return mixed Object payment details
     * @throws Exception
     */
    public function getPaymentDetails($paymentId)
    {
        try {
            $paymentDetails = Payment::get($paymentId, $this->apiContext);
        } catch (PayPalConnectionException $exception) {
            $this->setErrorMessageAndLogging($exception, 1);
            return false;
        } catch (PayPalConfigurationException $exception) {
            $this->setErrorMessageAndLogging($exception, 2);
            return false;
        } catch (PayPalInvalidCredentialException $exception) {
            $this->setErrorMessageAndLogging($exception, 3);
            return false;
        } catch (PayPalMissingCredentialException $exception) {
            $this->setErrorMessageAndLogging($exception, 4);
            return false;
        } catch (Exception $exception) {
            $this->setErrorMessageAndLogging($exception, 5);
            return false;
        }

        return $paymentDetails;
    }

    /**
     * Execute main service
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function execute(Request $request)
    {
        try {
            return $this->makePayment($request);
        } catch (Exception $exception) {
            $this->setErrorMessageAndLogging($exception, 1);
            return false;
        }
    }

    /**
     * Make a payment
     *
     * @param Request $request
     *
     * @return mixed
     */
    abstract public function makePayment(Request $request);

    /**
     * Use this function to perform more logic after user has made a payment
     *
     * @param Request $request
     *
     * @return mixed
     */
    abstract public function afterMakePayment(Request $request);
}
