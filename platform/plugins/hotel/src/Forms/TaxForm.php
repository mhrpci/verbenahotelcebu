<?php

namespace Botble\Hotel\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Hotel\Http\Requests\TaxRequest;
use Botble\Hotel\Models\Tax;

class TaxForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {

        $this
            ->setupModel(new Tax)
            ->setValidatorClass(TaxRequest::class)
            ->withCustomFields()
            ->add('title', 'text', [
                'label'      => trans('plugins/hotel::tax.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/hotel::tax.title'),
                    'data-counter' => 120,
                ],
            ])
            ->add('percentage', 'number', [
                'label'      => trans('plugins/hotel::tax.percentage'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/hotel::tax.percentage'),
                    'data-counter' => 120,
                ],
            ])
            ->add('priority', 'number', [
                'label'      => trans('plugins/hotel::tax.priority'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/hotel::tax.priority'),
                    'data-counter' => 120,
                ],
            ])
            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => BaseStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');
    }
}
