<?php

namespace App\Http\Requests\Store;

use App\Rules\CheckProductAvailabilityRule;
use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'order_type' => 'required',
            'total_order' => 'required|integer',
            'sales' => 'required|numeric',
            'discount' => 'required|numeric',
            'vat' => 'required|numeric',
            'amount' => 'required|numeric',
            'cash' => 'required|numeric|gte:amount',
            'change' => 'required|numeric',
            'items' => 'required',
            'items.*' => ['required', new CheckProductAvailabilityRule()],
            'items.*.quantity' => 'required|gt:0|integer',
        ];
    }
}
