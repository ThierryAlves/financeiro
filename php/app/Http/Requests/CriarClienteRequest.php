<?php

namespace App\Http\Requests;

class CriarClienteRequest extends ClienteRequest
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
                'nome' => ['required'],
                'documento' => ['required', 'unique:App\Models\Cliente,documento'],
                'email' => ['required', 'unique:App\Models\Cliente'],
                'senha' => ['required'],
                'telefone' => ['required']
            ]
        );
    }

    public function messages()
    {
        return [
            'nome.required' => 'Nome é obrigatório.'
        ];
    }
}
