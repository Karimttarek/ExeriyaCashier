<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StorePosRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'document_uuid' => ['max:255',
                Rule::unique('invoicehead' ,'document_uuid')->where(function ($query) {
                    return $query->where('invoice_type', str_contains(url()->current(), 'return') ? 6  : 5);
                })
            ],
            // 'invoice_date' => auth()->user()->role != 3 ? ['required'] : '',
            'items_count' => ['numeric','min:1'],
        ];
    }
}
