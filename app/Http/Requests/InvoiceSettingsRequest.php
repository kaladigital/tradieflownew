<?php
/**
 * Created by PhpStorm.
 * User: brainfors
 * Date: 4/29/21
 * Time: 2:57 PM
 */

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class InvoiceSettingsRequest extends FormRequest
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
            'company_name' => 'required|string',
            'country_id' => 'required|int',
            'zip_code' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'address' => 'required|string',
            'bank_account_holder_name' => 'required|string',
            'bank_account_holder_type' => 'required|string',
            'bank_account_country_id' => 'required|int',
            'bank_account_currency' => 'required|string',
            'bank_account_number' => 'required|string'
        ];
    }
    /**
     * Return errors
     * @param array $errors
     * @return JsonResponse
     */
    public function response(array $errors) {
        return new JsonResponse(['request_errors' => $errors], 422);
    }
}
