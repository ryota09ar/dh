<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            "family_name" => "required",
            "first_name" => "required",
            "email" => "required|unique:users",
            "password" => "required|unique:users",
        ];
    }

    public function messages(){
        return [
            "family_name.required"=>"名前を入力してください",
            "first_name.required"=>"名前を入力してください",
            "email.required"=>"メールを入力してください",
            "email.unique"=>"すでに存在するメールアドレスです",
            "password.required"=>"パスワードを入力してください",
            "password.unique"=>"他のパスワードにしてください"
        ];
    }
}
