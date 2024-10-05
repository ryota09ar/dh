<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends FormRequest
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
            "email" => "required|exists:users,email",
            "password" => "required",
        ];
    }

    public function messages(): array
    {
        return [
            "email.required" => "メールアドレスを入力してください",
            "email.exists" => "このメールアドレスは登録されていません",
            "password.required" => "パスワードを入力してください"
        ];
    }
}
