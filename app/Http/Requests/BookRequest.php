<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
            'name' => 'required|string',
            'price' => 'required',
            'quantity' => 'required',
            'description' => 'required|string|max:3000',
            'product_image' => 'sometimes|nullable|array',
            'product_iamge.*'=> 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'auhthor_id'=> 'required|exists:authors,id',
            'publisher_id'=> 'required|exists:publisher,id',
            'category_id'=> 'required|exists:categories,id',

        ];
    }
}
