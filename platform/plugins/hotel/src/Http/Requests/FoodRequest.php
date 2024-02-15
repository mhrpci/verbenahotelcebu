<?php

namespace Botble\Hotel\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class FoodRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'         => 'required',
            'price'        => 'required',
            'food_type_id' => 'required',
            'status'       => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
