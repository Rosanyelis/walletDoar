<?php

namespace App\Http\Requests\User;

use App\Http\Helpers\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CardIssueRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'card_name' => 'required|string|min:4|max:50',
            'card_type' => 'required|string',
            'card_provider' => 'required|string',
            'card_currency' => 'required|string',
            'from_currency' => 'required|string|exists:currencies,code',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    /**
     * Get the error messages for the defined validation rules.*
     * @return array
     */
    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            $response = Response::validation($validator->errors()->all(),null);
            throw new HttpResponseException($response);
          }

          parent::failedValidation($validator);
    }
}
