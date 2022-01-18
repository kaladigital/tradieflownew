<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as Request;
use Illuminate\Http\JsonResponse;

class RegisterRequest extends Request
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
            'name' => 'required',
            'email' => 'required|unique:user'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please specify your name',
            'email.required' => 'Please specify email',
            'email.unique' => 'Email already taken'
        ];
    }
}
