<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AtualizarClienteRequest extends FormRequest
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
        return array_merge_recursive(
            parent::rules(),
            [
                'placeholder' => ['required_without_all:nome,documento,email,senha,telefone'],
                'nome' => ['sometimes'],
                'documento' => ['sometimes', 'unique:App\Models\Cliente,documento'],
                'email' => ['sometimes', 'unique:App\Models\Cliente'],
                'senha' => ['sometimes'],
                'telefone' => ['sometimes']
            ]
        );
    }

    public function messages()
    {
        return [
            'required_without_all' => 'Deve ser enviado ao menos um campo para atualizar.'
        ];
    }
}
