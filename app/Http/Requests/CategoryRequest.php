<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isCompany());
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:contract,classified']
        ];

        // For updates, make sure the name is unique except for the current category
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['name'][] = 'unique:categories,name,' . $this->category->id . ',id,type,' . $this->input('type');
        } else {
            $rules['name'][] = 'unique:categories,name,NULL,id,type,' . $this->input('type');
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la categoría es obligatorio.',
            'name.unique' => 'Ya existe una categoría con este nombre para este tipo.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'type.required' => 'El tipo de categoría es obligatorio.',
            'type.in' => 'El tipo de categoría debe ser "contract" o "classified".'
        ];
    }
}
