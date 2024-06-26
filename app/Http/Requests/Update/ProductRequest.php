<?php

namespace App\Http\Requests\Update;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'image' => filled($this->request->get('image')) ? 'image|mimes:jpg,jpeg,png|max:10000' : '',
        ];
    }
}
