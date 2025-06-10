<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnemployedRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'profession' => ['required', 'string', 'max:255'],
            'experience' => ['nullable', 'string'],
            'experience_years' => ['required', 'integer', 'min:0', 'max:50'],
            'experience_level' => ['required', 'in:junior,medio,senior,lead'],
            'location' => ['required', 'string', 'max:255'],
            'skills' => ['nullable', 'string'],
            'education' => ['nullable', 'string'],
            'cv' => ['nullable', 'file', 'mimes:pdf', 'max:5120'], // 5MB max
            'expected_salary' => ['nullable', 'numeric', 'min:0'],
            'remote_work' => ['nullable', 'boolean'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'availability' => ['nullable', 'in:immediate,two_weeks,one_month,negotiable'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'profession.required' => 'La profesión es obligatoria.',
            'experience_years.required' => 'Los años de experiencia son obligatorios.',
            'experience_years.integer' => 'Los años de experiencia deben ser un número entero.',
            'experience_years.min' => 'Los años de experiencia no pueden ser negativos.',
            'experience_years.max' => 'Los años de experiencia no pueden ser más de 50.',
            'experience_level.required' => 'El nivel de experiencia es obligatorio.',
            'experience_level.in' => 'El nivel de experiencia debe ser junior, medio, senior o lead.',
            'location.required' => 'La ubicación es obligatoria.',
            'cv.mimes' => 'El CV debe ser un archivo PDF.',
            'cv.max' => 'El CV no puede pesar más de 5MB.',
            'expected_salary.numeric' => 'El salario esperado debe ser un número.',
            'expected_salary.min' => 'El salario esperado no puede ser negativo.',
            'bio.max' => 'La biografía no puede tener más de 1000 caracteres.',
            'availability.in' => 'La disponibilidad debe ser inmediata, dos semanas, un mes o negociable.',
        ];
    }
}
