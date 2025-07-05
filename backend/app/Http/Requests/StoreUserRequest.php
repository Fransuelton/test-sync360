<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'profile_image' => 'nullable|url|max:500',
            'full_name' => 'required|string|max:255|min:2',
            'age' => 'required|integer|min:1|max:120',
            'street' => 'required|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'state' => 'required|string|size:2',
            'biography' => 'required|string|max:1000',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'O nome completo é obrigatório.',
            'full_name.min' => 'O nome deve ter pelo menos 2 caracteres.',
            'age.required' => 'A idade é obrigatória.',
            'age.min' => 'A idade deve ser pelo menos 1 ano.',
            'age.max' => 'A idade não pode ser maior que 120 anos.',
            'state.size' => 'O estado deve ter exatamente 2 caracteres.',
            'biography.max' => 'A biografia não pode ter mais de 1000 caracteres.',
        ];
    }
}
