<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => ['min:8', 'max:80'],
            'documento' => ['min:11', 'max:18'],
            'email' => ['email'],
            'senha' => ['min:8'],
            'telefone' => ['min:8', 'max:21']
        ];
    }

    public function prepareForValidation()
    {
        $input = $this->all();

        if ($this->has('documento')) {
            $input['documento'] = str_replace(['-', ' ', '.', '/'], '', $this->get('documento'));
        }

        if ($this->has('telefone')) {
            $input['telefone'] = str_replace(['(',')',' ','-'], '', $this->get('telefone'));
        }

        $this->replace($input);
    }
}
