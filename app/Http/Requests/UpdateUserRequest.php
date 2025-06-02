<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user')->id;

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($userId),
            ],
            'password' => 'nullable|min:8|confirmed',
            'is_admin' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The full name is required',
            'email.unique' => 'This email is already in use',
            'password.confirmed' => 'Password confirmation does not match',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->password === null) {
            $this->request->remove('password');
        }
    }

}
