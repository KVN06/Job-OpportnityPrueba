<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobApplicationRequest extends FormRequest
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
        $rules = [
            'cover_letter' => ['required', 'string', 'min:100', 'max:2000'],
            'expected_salary' => ['nullable', 'numeric', 'min:0'],
            'resume' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'], // 5MB max
            'skills' => ['nullable', 'array'],
            'skills.*' => ['string', 'max:100'],
            'availability_date' => ['nullable', 'date', 'after_or_equal:today'],
            'references' => ['nullable', 'array'],
            'references.*.name' => ['required_with:references', 'string', 'max:255'],
            'references.*.position' => ['required_with:references', 'string', 'max:255'],
            'references.*.company' => ['required_with:references', 'string', 'max:255'],
            'references.*.email' => ['required_with:references', 'email', 'max:255'],
            'references.*.phone' => ['required_with:references', 'string', 'max:20'],
        ];

        // Si es una actualización de estado por parte de la empresa
        if ($this->isMethod('PATCH') && $this->route('application')) {
            $rules = [
                'status' => ['required', 'in:pending,review,accepted,rejected'],
                'feedback' => ['nullable', 'string', 'max:1000'],
            ];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'cover_letter.required' => 'La carta de presentación es obligatoria.',
            'cover_letter.min' => 'La carta de presentación debe tener al menos 100 caracteres.',
            'cover_letter.max' => 'La carta de presentación no puede tener más de 2000 caracteres.',
            'expected_salary.numeric' => 'El salario esperado debe ser un número.',
            'expected_salary.min' => 'El salario esperado no puede ser negativo.',
            'resume.mimes' => 'El currículum debe ser un archivo PDF, DOC o DOCX.',
            'resume.max' => 'El currículum no puede pesar más de 5MB.',
            'skills.*.max' => 'Las habilidades no pueden tener más de 100 caracteres.',
            'availability_date.date' => 'La fecha de disponibilidad debe ser una fecha válida.',
            'availability_date.after_or_equal' => 'La fecha de disponibilidad debe ser hoy o una fecha futura.',
            'references.*.name.required_with' => 'El nombre de la referencia es obligatorio.',
            'references.*.position.required_with' => 'El cargo de la referencia es obligatorio.',
            'references.*.company.required_with' => 'La empresa de la referencia es obligatoria.',
            'references.*.email.required_with' => 'El email de la referencia es obligatorio.',
            'references.*.email.email' => 'El email de la referencia debe ser válido.',
            'references.*.phone.required_with' => 'El teléfono de la referencia es obligatorio.',
            'status.required' => 'El estado de la aplicación es obligatorio.',
            'status.in' => 'El estado debe ser pendiente, en revisión, aceptado o rechazado.',
            'feedback.max' => 'La retroalimentación no puede tener más de 1000 caracteres.',
        ];
    }
}
