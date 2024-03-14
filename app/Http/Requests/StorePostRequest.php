<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
    { // Create mutipleform in 1 file for case meet
        return [
            'phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'username' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'password' => 'required',
            'user_id' => 'required',
            'book_id' => 'required',
            'amount' => 'required',
            'province.' => 'required',
            'distric' => 'required',
            'ward' => 'required',
            'commue' =>'required',
            'address' => 'required',
            'avatar.*' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',

        ];
    }
    public function messages(): array
    {
        return [
            'phone.regex' => 'Số điện thoại không hợp lệ.',
            'phone.min' => 'Số điện thoại phải có ít nhất 10 ký tự.',
            'email.email' => 'Email không hợp lệ.',
            'username.string' => 'Tên người dùng phải là một chuỗi ký tự.',
            'username.max' => 'Tên người dùng không được vượt quá 255 ký tự.',
            'password.required' => 'Mật khẩu không được để trống',
            'province.required' => 'Thành phố để trống ',
            'distric.required' => 'Quận đang để trống',
            'ward.required' => 'Huyện dang để trống',
            'commue.required' =>'Xã đang để trống',
            'address.required' => 'Địa chỉ để trống',

        ];
    }
}
