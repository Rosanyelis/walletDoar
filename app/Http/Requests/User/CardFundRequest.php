<?php

namespace App\Http\Requests\User;

use App\Http\Helpers\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CardFundRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id'            => 'required|integer',
            'amount'        => 'required|numeric|gt:0',
            'card_currency' => 'required|string',
            'from_currency' => 'required|string',
        ];
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
