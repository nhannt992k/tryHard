<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
    {
        return [
            'name' => 'sometimes|nullable|string',
            'phone' => 'sometimes|nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'username' => 'sometimes|nullable|string|max:255',
            'email' => 'sometimes|nullable|email',
            'avatar' => 'sometimes|nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'password' => 'required|string',
        ];
    }
}
