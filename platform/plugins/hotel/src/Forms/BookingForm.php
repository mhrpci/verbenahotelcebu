<?php

namespace Botble\Hotel\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Hotel\Enums\BookingStatusEnum;
use Botble\Hotel\Http\Requests\BookingRequest;
use Botble\Hotel\Models\Booking;

class BookingForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $this
            ->setupModel(new Booking)
            ->setValidatorClass(BookingRequest::class)
            ->withCustomFields()
            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => BookingStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status')
            ->addMetaBoxes([
                'information' => [
                    'title'      => trans('plugins/hotel::booking.booking_information'),
                    'content'    => view('plugins/hotel::booking-info', ['booking' => $this->getModel()])->render(),
                    'attributes' => [
                        'style' => 'margin-top: 0',
                    ],
                ],
            ]);
    }
}
