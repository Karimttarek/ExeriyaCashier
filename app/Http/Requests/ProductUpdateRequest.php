<?php

namespace App\Http\Requests;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [

            'name' => ['required', 'string','max:100', Rule::unique('products' , 'name')->ignore($this->uuid, 'uuid')],
            'name_ar' => ['required', 'string','max:100', Rule::unique('products' , 'name_ar')->ignore($this->uuid, 'uuid')],

            'item_code' => ['required'],

            'bar_code' =>  ['nullable', Rule::unique('products' , 'bar_code')->ignore($this->uuid, 'uuid')],

            'discount' => ['numeric', 'min:0','nullable'],
            'stock' => ['numeric','nullable'],
            'parent_code' => ['required_if:code_type,EGS'],
            'unit' => ['required'],

            'first_unit_type' => ['required'],
            'first_unit_qty' => ['required'],
            'first_unit_pur_price' => ['required'],
            'first_unit_sell_price' => ['required'],

            'second_unit_type' => ['sometimes'],
            'second_unit_qty' => ['required_with:second_unit_type'],
            'second_unit_pur_price' => ['required_with:second_unit_type'],
            'second_unit_sell_price' => ['required_with:second_unit_type'],

            'third_unit_type' => ['sometimes'],
            'third_unit_qty' => ['required_with:third_unit_type'],
            'third_unit_pur_price' => ['required_with:third_unit_type'],
            'third_unit_sell_price' => ['required_with:third_unit_type'],
        ];
    }
}
