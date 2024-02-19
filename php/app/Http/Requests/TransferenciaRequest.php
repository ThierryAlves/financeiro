<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferenciaRequest extends FormRequest
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
            'recebedor_id' => ['required', 'exists:clientes,id'],
            'valor' => ['required', 'decimal:0,2', 'min:0.01'],
        ];
    }

    public function messages()
    {
        return [
            'recebedor_id.required' => "É obrigatório informar o recebedor da transferência",
            'recebedor_id.exists' => "Cliente recebedor não foi encontrado",
            'valor.required' => "É obrigatório informar o valor da transferência",
            'valor.decimal' => "Valor informado é invalido",
            'valor.min' => "Valor informado deve ser de no minimo R$ 0.01",
        ];
    }
}
