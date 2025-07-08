<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'profile_image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'full_name' => 'sometimes|string|max:255|min:2',
            'age' => 'sometimes|integer|min:1|max:120',
            'street' => 'sometimes|string|max:255',
            'neighborhood' => 'sometimes|string|max:255',
            'state' => 'sometimes|string|size:2',
            'biography' => 'sometimes|string|max:1000',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'profile_image.image' => 'O arquivo deve ser uma imagem válida.',
            'profile_image.mimes' => 'A imagem deve ser do tipo: jpeg, png, jpg ou gif.',
            'profile_image.max' => 'A imagem não pode ser maior que 2MB.',
            'full_name.min' => 'O nome deve ter pelo menos 2 caracteres.',
            'age.min' => 'A idade deve ser pelo menos 1 ano.',
            'age.max' => 'A idade não pode ser maior que 120 anos.',
            'state.size' => 'O estado deve ter exatamente 2 caracteres.',
            'biography.max' => 'A biografia não pode ter mais de 1000 caracteres.',
        ];
    }
}
