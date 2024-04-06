<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ManufacturStoreRequest extends FormRequest
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
            'parent' => ['required',Rule::unique('manufacturs' , 'parent_uuid')],
//            'number' => ['required'],
//            'uuid' => ['required' ,'exists:products,uuid'],
//            'item' => ['required','exists:products'],
//            'qty' => ['required' ,'min:1'],
        ];
    }
}
