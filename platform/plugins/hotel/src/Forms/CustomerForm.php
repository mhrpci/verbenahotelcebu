<?php

namespace Botble\Hotel\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Hotel\Http\Requests\CustomerCreateRequest;
use Botble\Hotel\Models\Customer;

class CustomerForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $this
            ->setupModel(new Customer)
            ->setValidatorClass(CustomerCreateRequest::class)
            ->withCustomFields()
            ->add('first_name', 'text', [
                'label'      => trans('plugins/hotel::customer.form.first_name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/hotel::customer.form.first_name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('last_name', 'text', [
                'label'      => trans('plugins/hotel::customer.form.last_name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/hotel::customer.form.last_name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('email', 'text', [
                'label'      => trans('plugins/hotel::customer.form.email'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/hotel::customer.form.email_placeholder'),
                    'data-counter' => 60,
                ],
            ])
            ->add('phone', 'text', [
                'label'      => trans('plugins/hotel::customer.form.phone'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'placeholder'  => trans('plugins/hotel::customer.form.phone'),
                    'data-counter' => 20,
                ],
            ])
            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => BaseStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');
    }
}
