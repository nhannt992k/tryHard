<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [    
            'province' => 'required|string',
            'district' => 'required|string',
            'ward' => 'required|string',
            'commue' => 'required|string',
            'address' => 'required|string',
            'is_default' => 'required|boolean',
        ];
    }
}
