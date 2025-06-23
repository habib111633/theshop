<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'billing_zip'    => 'required|max:20',
            'billing_phone'  => 'required|max:20',
            'payment_method' => 'required|in:cod,bank',
            // Add more rules as needed
        ];
    }
}
