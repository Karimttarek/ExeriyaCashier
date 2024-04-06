<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class InvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'invoice_date' => 'required',
//            'issuer_id' => 'required',
            // 'issuer_name' => 'required',
            // 'issuer_country' => 'required',
            // 'issuer_gov' => 'required',
            // 'issuer_city' => 'required',
            // 'issuer_building_number' => 'required',
            // 'issuer_street' => 'required',

            // 'customer_id' => 'required',
            'customer_name' => 'required',
            'customer_country' => 'required',
            'customer_gov' => 'required',
            'customer_city' => 'required',
            'customer_building_number' => 'required',
            'customer_street' => 'required',

            // 'item' => 'required',
            // 'description' => 'required'
        ];
    }
}
