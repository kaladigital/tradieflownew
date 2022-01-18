<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as Request;
use Illuminate\Http\JsonResponse;

class InvoiceRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'country_id' => 'required',
            'issued_date' => 'required',
            'due_date' => 'required',
            'currency' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'address' => 'Please specify address',
            'city' => 'Please specify city',
            'state' => 'Please specify state',
            'zip' => 'Please specify zip',
            'country_id' => 'Please specify country',
            'issued_date' => 'Please specify issued date',
            'due_date' => 'Please specify due date',
            'currency' => 'Please specify currency'
        ];
    }
}
