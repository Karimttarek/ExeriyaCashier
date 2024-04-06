<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class InvoiceUpdateRequest extends FormRequest
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
        // if (str_contains(url()->current() , 'purchase')) {
        //     $this->type = 1;
        // }
        return [
            'invoice_number' => ['max:255',
                Rule::unique('invoicehead' ,'invoice_number')->where(function ($query) {
                return $query->where('invoice_type', $this->type);
                })
                ->ignore($this->uuid, 'uuid')
            ],

            'internal_id' =>  ['required','max:255',
                Rule::unique('invoicehead' ,'internal_id')->where(function ($query) {
                return $query->where('invoice_type', 1);
                })
                ->ignore($this->uuid, 'uuid')
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
