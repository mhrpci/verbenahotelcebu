<?php

namespace Botble\Hotel\Http\Requests;

use Botble\Hotel\Enums\BookingStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class BookingRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'status' => Rule::in(BookingStatusEnum::values()),
        ];

        if (setting('enable_captcha') && is_plugin_active('captcha')) {
            $rules += [
                'g-recaptcha-response' => 'required|captcha',
            ];
        }

        return $rules;
    }
}
