<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductStoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:products,name'],
            'name_ar' => ['required', 'string', 'max:255', 'unique:products,name_ar'],
            'item_code' => ['required'],
            'bar_code' => ['nullable','unique:products,bar_code'],

            // 'purchase_price' => ['required','numeric', 'min:1'],
            // 'sell_price' => ['required','numeric', 'min:1'],

            'discount' => ['numeric', 'min:0','nullable'],
            'stock' => ['numeric','nullable'],
            'parent_code' => ['required_if:code_type,EGS'],
            'unit' => ['required'],

            'first_unit_type' => ['sometimes'],
            'first_unit_qty' => ['required_with:first_unit_type'],
            'first_unit_pur_price' => ['required_with:first_unit_type'],
            'first_unit_sell_price' => ['required_with:first_unit_type'],

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
