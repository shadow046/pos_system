<?php

namespace App\Http\Requests\Update;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => "required|email:rfc,dns|unique:users,email,{$this->user->id}",
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'status' => 'required|string',
            'role' => 'required|string',
        ];
    }
}
