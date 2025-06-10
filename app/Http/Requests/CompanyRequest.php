<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'company_name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'industry' => ['required', 'string', 'max:100'],
            'website' => ['nullable', 'url', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'size' => ['nullable', 'integer', 'min:1'],
            'founded_year' => ['nullable', 'integer', 'min:1800', 'max:' . date('Y')],
            'logo' => ['nullable', 'image', 'max:2048'], // 2MB max
        ];

        // Add specific rules for updates
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules = array_map(function ($rule) {
                return array_diff($rule, ['required']);
            }, $rules);
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'company_name.required' => 'El nombre de la empresa es obligatorio.',
            'company_name.max' => 'El nombre de la empresa no puede tener más de :max caracteres.',
            'description.required' => 'La descripción de la empresa es obligatoria.',
            'location.required' => 'La ubicación de la empresa es obligatoria.',
            'location.max' => 'La ubicación no puede tener más de :max caracteres.',
            'industry.required' => 'El sector de la empresa es obligatorio.',
            'industry.max' => 'El sector no puede tener más de :max caracteres.',
            'website.url' => 'El sitio web debe ser una URL válida.',
            'linkedin.url' => 'El perfil de LinkedIn debe ser una URL válida.',
            'size.integer' => 'El número de empleados debe ser un número entero.',
            'size.min' => 'El número de empleados debe ser al menos :min.',
            'founded_year.integer' => 'El año de fundación debe ser un número entero.',
            'founded_year.min' => 'El año de fundación no puede ser anterior a :min.',
            'founded_year.max' => 'El año de fundación no puede ser posterior a :max.',
            'logo.image' => 'El logo debe ser una imagen.',
            'logo.max' => 'El logo no puede pesar más de 2MB.',
        ];
    }
}
