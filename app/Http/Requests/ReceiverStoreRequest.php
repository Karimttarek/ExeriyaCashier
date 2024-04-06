<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReceiverStoreRequest extends FormRequest
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
            'customer_name' => ['required','string', 'max:255'],
            'customer_id' => ['required_if:customer_type,B',Rule::when($this->input('customer_type') === 'B', 'min:9')],
            'customer_country' => ['required'],
            'customer_gov' => ['required'],
            'customer_city' => ['required'],
            'customer_building_number' => ['required'],
            'customer_street' => ['required'],
        ];
    }
}
