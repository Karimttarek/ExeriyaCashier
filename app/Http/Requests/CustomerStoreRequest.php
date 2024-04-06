<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CustomerStoreRequest extends FormRequest
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
            'name' => ['string', 'max:255', 'unique:customers,name'],
            'tax_code' => ['required_if:type,B'],
            'email ' => ['email', 'max:255', 'unique:customers,email'],
            'mobile ' => ['max:255', 'unique:customers,mobile '],
             'country' => ['required'],
             'gov' => ['required'],
             'city' => ['required'],
             'building_number' => ['required'],
             'street' => ['required'],
        ];
    }
}
