<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormSubscribe extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|unique:io_subscribe,email',
        ];
    }
    public function messages()
    {
        return [
            'email.required' => 'Email của bạn chưa có!',
            'email.unique' =>'Email của bạn đã có trong hệ thống của chúng tôi! xin vui lòng nhập email khác',
            'email.email' =>'Email của bạn không đúng định dạng'
        ];
    }
}
