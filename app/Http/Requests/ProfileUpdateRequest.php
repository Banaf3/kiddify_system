<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'phone_number' => ['required', 'string', 'max:15'],
            'gender' => ['required', 'in:male,female'],
            'address' => ['required', 'string', 'max:500'],
            'date_of_birth' => ['required', 'date', 'before_or_equal:2000-12-31'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'min:8'],
        ];
    }
}
