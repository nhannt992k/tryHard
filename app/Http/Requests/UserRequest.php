<?php

namespace App\Http\Requests;

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
    {dd($this->all());
        return [
            'name' => 'sometimes|nullable|string',
            'phonenumber' => 'sometimes|nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'username' => 'sometimes|nullable|string|max:255',
            'email' => 'sometimes|nullable|email',
            'avatar' => 'sometimes|nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'password' => 'required|string',
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (empty($this->username) && empty($this->phonenumber) && empty($this->email)) {
                $validator->errors()->add('fields', 'At least one of username, phonenumber or email is required');
            }
        });
    }
}
