<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:100', 'alpha_dash'],
            'email' => ['required', 'max:100', 'email'],
            'password' => ['required']
        ];
    }


    
  protected function failedValidation(Validator $validator)
  {
    throw new HttpResponseException(response([
        "errors" => $validator->getMessageBag()
    ], 400));
  }


}
