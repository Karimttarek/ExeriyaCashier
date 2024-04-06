<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VoucherStoreRequest extends FormRequest
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
            'no' => ['required',Rule::unique('receipts' ,'no')->where(function ($query) {
                return $query->whereIn('receipt_type', [1,9,11]); })->ignore($this->no)],
            'receipt_date' => ['required'],
            'value' => ['required']
        ];
    }
}
