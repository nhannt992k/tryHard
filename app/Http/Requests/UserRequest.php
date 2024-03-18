<?php

namespace App\Http\Requests;

use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UserRequest extends FormRequest
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
    {/* dd($this->all()); */
        return [
            'name' => 'sometirequiredmes|nullable|string',
            'phonenumber' => 'required|nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'username' => 'required|nullable|string|max:255',
            'email' => 'required|nullable|email',
            'avatar' => 'sometimes|nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'password' => 'required|string',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if (empty($this->username) || empty($this->phonenumber) || empty($this->email)) {
                    $validator->errors()->add(
                        'field',
                        'Something is wrong with this field!'
                    );
                }
            }

        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {dd($this->getAttributes());
        return [
            'phonenumber.required' => 'A phonenumber is required',
            'email.required' => 'A email is required',
        ];
    }
}
