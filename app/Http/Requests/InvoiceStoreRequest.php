<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class InvoiceStoreRequest extends FormRequest
{
    private $type;
    public function __construct(int $type = 1) {
        $this->type = $type;
    }
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
        // switch (url()->current()) {
        //     case str_contains(url()->current(), 'purchase/create'):
        //         $this->type = 1;
        //       break;
        //     case str_contains(url()->current(), 'sales/create'):
        //         $this->type = 2;
        //       break;
        //     case str_contains(url()->current() , 'purchase/return'):
        //         $this->type = 3;
        //       break;
        //     case str_contains(url()->current() , 'sales/return'):
        //         $this->type = 4;
        //       break;
        //   }

        return [
            'invoice_number' => ['max:255',
                Rule::unique('invoicehead' ,'invoice_number')->where(function ($query) {
                    return $query->where('invoice_type', $this->type);
                })
            ],
            'internal_id' =>  ['required','max:255',
                Rule::unique('invoicehead' ,'internal_id')->where(function ($query) {
                    return $query->where('invoice_type', $this->type);
                })
            ],
            'invoice_date' => 'required',
            'customer_name' => 'required',
            'customer_country' => 'required',
            'customer_gov' => 'required',
            'customer_city' => 'required',
            'customer_building_number' => 'required',
            'customer_street' => 'required',
        ];
    }
}
