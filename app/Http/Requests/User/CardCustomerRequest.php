<?php

namespace App\Http\Requests\User;

use App\Http\Helpers\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CardCustomerRequest extends FormRequest
{
      /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        if (request()->routeIs('*.create.customer')) {
            return [
                'first_name'     => 'required|string|max:100',
                'last_name'      => 'required|string|max:100',
                'email'          => 'required|email|max:150|unique:cardyfie_card_customers,email',
                'date_of_birth'  => 'required|date',
                'id_type'        => 'required|in:nid,passport,bvn',
                'id_number'      => 'required|string|max:50',
                'id_front_image' => 'required|image|mimes:jpg,jpeg,png,svg,webp|max:10240',
                'id_back_image'  => 'required|image|mimes:jpg,jpeg,png,svg,webp|max:10240',
                'user_image'     => 'required|image|mimes:jpg,jpeg,png,svg,webp|max:10240',
                'house_number'   => 'required|string|max:150',
                'country'        => 'required|string|max:100',
                'city'           => 'required|string|max:100',
                'state'          => 'required|string|max:100',
                'zip_code'       => 'required|string|max:20',
                'address_line_1' => 'required|string|max:255',
            ];
        } else if (request()->routeIs('*.update.customer')) {
            return [
                'first_name'     => 'required|string|max:100',
                'last_name'      => 'required|string|max:100',
                'date_of_birth'  => 'required|date',
                'id_type'        => 'required|in:nid,passport,bvn',
                'id_number'      => 'required|string|max:50',
                'id_front_image' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:10240',
                'id_back_image'  => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:10240',
                'user_image'     => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:10240',
                'house_number'   => 'required|string|max:150',
                'city'           => 'required|string|max:100',
                'state'          => 'required|string|max:100',
                'zip_code'       => 'required|string|max:20',
                'address_line_1' => 'required|string|max:255',
            ];
        }

        return [];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
          if ($this->expectsJson()) {
            $response = Response::validation($validator->errors()->all(),null);
            throw new HttpResponseException($response);
          }

          parent::failedValidation($validator);
    }

}
