<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('email') && ! $this->filled('identity')) {
            $this->merge([
                'identity' => $this->input('email'),
            ]);
        }

        if ($this->has('remember')) {
            $this->merge([
                'remember' => $this->boolean('remember'),
            ]);
        }
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'identity' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'identity.required' => 'Identitas (username, surel, atau NIP) wajib diisi.',
            'identity.string' => 'Identitas harus berupa teks yang valid.',
            'identity.max' => 'Identitas tidak boleh lebih dari 255 karakter.',
            'password.required' => 'Kata sandi wajib diisi.',
            'remember.boolean' => 'Pilihan pengingat sesi harus bernilai benar atau salah.',
        ];
    }
}
