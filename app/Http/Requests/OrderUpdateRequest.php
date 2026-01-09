<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'order_number' => ['required', 'string', 'max:50', 'unique:orders,order_number'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'status' => ['required', 'in:pending,processing,shipped,delivered,cancelled'],
            'total_amount' => ['required', 'numeric', 'between:-99999999.99,99999999.99'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'shipping_city' => ['required', 'string', 'max:100'],
            'shipping_postal_code' => ['required', 'string', 'max:20'],
            'payment_method' => ['required', 'in:cash_on_delivery,card,paypal'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
