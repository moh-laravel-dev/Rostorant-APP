<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => ['required', 'numeric'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'يرجى إدخال رقم الهاتف.',
            'phone.numeric' => 'يجب أن يكون رقم الهاتف عدداً.',
            'password.required' => 'يرجى إدخال كلمة المرور.',
            'remember.boolean' => 'قيمة تذكرني غير صحيحة.',
        ];
    }

    public function attributes(): array
    {
        return [
            'phone' => 'رقم الهاتف',
            'password' => 'كلمة المرور',
            'remember' => 'تذكرني',
        ];
    }
}
