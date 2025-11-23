<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * 確定用戶是否有權進行此請求
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 獲取應用於請求的驗證規則
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
         return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
            'remember_me' => ['sometimes', 'boolean'],
        ];
    }


     /**
     * 獲取自定義的驗證錯誤消息
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => '請輸入電子郵件地址',
            'email.email' => '請輸入有效的電子郵件地址',
            'password.required' => '請輸入密碼',
            'password.min' => '密碼長度至少需要 :min 個字元',
        ];
    }

    /**
     * 獲取自定義的驗證屬性名稱
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'email' => '電子郵件',
            'password' => '密碼',
        ];
    }
}
