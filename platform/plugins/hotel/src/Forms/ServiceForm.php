<?php

namespace Botble\Hotel\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Hotel\Http\Requests\ServiceRequest;
use Botble\Hotel\Models\Service;
use Botble\Hotel\Repositories\Interfaces\CurrencyInterface;

class ServiceForm extends FormAbstract
{

    /**
     * @var CurrencyInterface
     */
    protected $currencyRepository;

    /**
     * ServiceForm constructor.
     * @param CurrencyInterface $currencyRepository
     */
    public function __construct(CurrencyInterface $currencyRepository)
    {
        parent::__construct();
        $this->currencyRepository = $currencyRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $currencies = $this->currencyRepository->pluck('ht_currencies.title', 'ht_currencies.id');

        $this
            ->setupModel(new Service)
            ->setValidatorClass(ServiceRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('description', 'textarea', [
                'label'      => trans('core/base::forms.description'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'rows'         => 4,
                    'placeholder'  => trans('core/base::forms.description_placeholder'),
                    'data-counter' => 400,
                ],
            ])
            ->add('content', 'editor', [
                'label'      => trans('core/base::forms.content'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'rows'            => 4,
                    'placeholder'     => trans('core/base::forms.description_placeholder'),
                    'with-short-code' => true,
                ],
            ])
            ->add('rowOpen1', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('price', 'text', [
                'label'      => trans('plugins/hotel::service.form.price'),
                'label_attr' => ['class' => 'control-label required'],
                'wrapper'    => [
                    'class' => 'form-group col-md-6',
                ],
                'attr'       => [
                    'id'          => 'price-number',
                    'placeholder' => trans('plugins/hotel::service.form.price'),
                    'class'       => 'form-control input-mask-number',
                ],
            ])
            ->add('currency_id', 'customSelect', [
                'label'      => trans('plugins/hotel::service.form.currency'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-md-6',
                ],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => $currencies,
            ])
            ->add('rowClose1', 'html', [
                'html' => '</div>',
            ])
            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => BaseStatusEnum::labels(),
            ])
            ->add('image', 'mediaImage', [
                'label'      => trans('core/base::forms.image'),
                'label_attr' => ['class' => 'control-label'],
            ])
            ->setBreakFieldPoint('status');
    }
}
