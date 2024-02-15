<?php

namespace Botble\Base\Supports;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;

class Core
{

    /**
     * @var string
     */
    protected $productId;

    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $verifyType;

    /**
     * @var int
     */
    protected $verificationPeriod;

    /**
     * @var string
     */
    protected $licenseFile;

    /**
     * @var string
     */
    protected $sessionKey = '44622179e10cab6';

    /**
     * Core constructor.
     */
    public function __construct()
    {
        $this->apiUrl = 'https://license.botble.com/';
        $this->apiKey = 'CAF4B17F6D3F656125F9';
        $this->verificationPeriod = 1;
        $this->licenseFile = storage_path('.license');

        $core = get_file_data(core_path('core.json'));

        if ($core) {
            $this->productId = Arr::get($core, 'productId');
            $this->verifyType = Arr::get($core, 'source');
            $this->apiUrl = Arr::get($core, 'apiUrl', $this->apiUrl);
            $this->apiKey = Arr::get($core, 'apiKey', $this->apiKey);
        }
    }

    /**
     * @return string
     */
    public function getLicenseFilePath()
    {
        return $this->licenseFile;
    }

    /**
     * @param string $license
     * @param string $client
     * @param bool $createLicense
     * @return array
     */
    public function activateLicense($license, $client, $createLicense = true)
    {
        $data = [
            'product_id'   => $this->productId,
            'license_code' => $license,
            'client_name'  => $client,
            'verify_type'  => $this->verifyType,
        ];

        $response = $this->callApi($this->apiUrl . 'api/activate_license', $data);

        if (!empty($createLicense)) {
            if ($response['status']) {
                $license = trim($response['lic_response']);
                file_put_contents($this->licenseFile, $license, LOCK_EX);
            } else {
                @chmod($this->licenseFile, 0777);
                if (is_writeable($this->licenseFile)) {
                    unlink($this->licenseFile);
                }
            }
        }

        return $response;
    }

    /**
     * @param string $url
     * @param string $data
     * @return array
     */
    protected function callApi(string $url, array $data = [])
    {
        $client = new Client;

        try {
            $result = $client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json',
                    'LB-API-KEY'   => $this->apiKey,
                    'LB-URL'       => rtrim(url('/'), '/'),
                    'LB-IP'        => Helper::getIpFromThirdParty(),
                    'LB-LANG'      => 'english',
                ],
                'json'    => $data,
            ]);
        } catch (Exception $exception) {
            return [
                'status'  => false,
                'message' => $exception->getMessage(),
            ];
        }

        if (!$result && config('app.debug')) {
            return [
                'status'  => false,
                'message' => 'Server is unavailable at the moment, please try again.',
            ];
        }

        $result = json_decode($result->getBody(), true);

        if (!$result['status']) {
            if (config('app.debug')) {
                return $result;
            }

            return [
                'status'  => false,
                'message' => 'Server returned an invalid response, please contact support.',
            ];
        }

        return $result;
    }

    /**
     * @param bool $timeBasedCheck
     * @param bool $license
     * @param bool $client
     * @return array
     */
    public function verifyLicense($timeBasedCheck = false, $license = false, $client = false)
    {
        $data = [
            'product_id'   => $this->productId,
            'license_file' => null,
            'license_code' => null,
            'client_name'  => null,
        ];

        if (!empty($license) && !empty($client)) {
            $data = [
                'product_id'   => $this->productId,
                'license_file' => null,
                'license_code' => $license,
                'client_name'  => $client,
            ];
        } elseif ($this->checkLocalLicenseExist()) {
            $data = [
                'product_id'   => $this->productId,
                'license_file' => file_get_contents($this->licenseFile),
                'license_code' => null,
                'client_name'  => null,
            ];
        }

        $response = [
            'status'  => true,
            'message' => 'Verified! Thanks for purchasing our product.',
        ];

        if ($timeBasedCheck && $this->verificationPeriod > 0) {
            $type = (int)$this->verificationPeriod;
            $today = date('d-m-Y');
            if (!session($this->sessionKey)) {
                session([$this->sessionKey => '00-00-0000']);
            }
            $typeText = $type . ' days';

            if ($type == 1) {
                $typeText = '1 day';
            } elseif ($type == 3) {
                $typeText = '3 days';
            } elseif ($type == 7) {
                $typeText = '1 week';
            }

            if (strtotime($today) >= strtotime(session($this->sessionKey))) {
                $response = $this->callApi($this->apiUrl . 'api/verify_license', $data);
                if ($response['status'] == true) {
                    $tomorrow = date('d-m-Y', strtotime($today . ' + ' . $typeText));
                    session([$this->sessionKey => $tomorrow]);
                }
            }

            return $response;
        }

        return $this->callApi($this->apiUrl . 'api/verify_license', $data);
    }

    /**
     * @return bool
     */
    public function checkLocalLicenseExist()
    {
        return is_file($this->licenseFile);
    }

    /**
     * @param bool $license
     * @param bool $client
     * @return array
     */
    public function deactivateLicense($license = false, $client = false)
    {
        $data = [];

        if (!empty($license) && !empty($client)) {
            $data = [
                'product_id'   => $this->productId,
                'license_file' => null,
                'license_code' => $license,
                'client_name'  => $client,
            ];
        } elseif (is_file($this->licenseFile)) {
            $data = [
                'product_id'   => $this->productId,
                'license_file' => file_get_contents($this->licenseFile),
                'license_code' => null,
                'client_name'  => null,
            ];
        }

        $response = $this->callApi($this->apiUrl . 'api/deactivate_license', $data);

        if ($response['status']) {
            session()->forget($this->sessionKey);
            @chmod($this->licenseFile, 0777);
            if (is_writeable($this->licenseFile)) {
                unlink($this->licenseFile);
            }
        }

        return $response;
    }
}
