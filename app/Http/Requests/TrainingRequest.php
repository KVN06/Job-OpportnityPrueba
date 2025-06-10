<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrainingRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'provider' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'link' => ['nullable', 'url', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:draft,published,archived'],
            'type' => ['required', 'in:online,in-person,hybrid'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'duration' => ['nullable', 'integer', 'min:1'],
            'duration_unit' => ['required_with:duration', 'in:hours,days,weeks,months'],
            'prerequisites' => ['nullable', 'array'],
            'prerequisites.*' => ['string', 'max:255'],
            'materials' => ['nullable', 'array'],
            'materials.*' => ['file', 'mimes:pdf,doc,docx,ppt,pptx,zip', 'max:10240'], // 10MB max
            'price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['required_with:price', 'string', 'size:3'],
            'certification' => ['nullable', 'boolean'],
            'skills' => ['required', 'array'],
            'skills.*' => ['string', 'max:100'],
            'level' => ['required', 'in:beginner,intermediate,advanced,expert'],
            'language' => ['required', 'string', 'max:50'],
        ];

        // Si es una actualización, hacemos algunos campos opcionales
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules = array_map(function ($rule) {
                return in_array('required', $rule) ? 
                    array_diff($rule, ['required']) : 
                    $rule;
            }, $rules);
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'El título es obligatorio.',
            'provider.required' => 'El proveedor es obligatorio.',
            'description.required' => 'La descripción es obligatoria.',
            'link.url' => 'El enlace debe ser una URL válida.',
            'start_date.required' => 'La fecha de inicio es obligatoria.',
            'start_date.date' => 'La fecha de inicio debe ser una fecha válida.',
            'end_date.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado debe ser borrador, publicado o archivado.',
            'type.required' => 'El tipo es obligatorio.',
            'type.in' => 'El tipo debe ser en línea, presencial o híbrido.',
            'capacity.integer' => 'La capacidad debe ser un número entero.',
            'capacity.min' => 'La capacidad debe ser al menos 1.',
            'duration.integer' => 'La duración debe ser un número entero.',
            'duration.min' => 'La duración debe ser al menos 1.',
            'duration_unit.required_with' => 'La unidad de duración es obligatoria cuando se especifica la duración.',
            'duration_unit.in' => 'La unidad de duración debe ser horas, días, semanas o meses.',
            'materials.*.mimes' => 'Los materiales deben ser archivos PDF, DOC, DOCX, PPT, PPTX o ZIP.',
            'materials.*.max' => 'Los materiales no pueden pesar más de 10MB.',
            'price.numeric' => 'El precio debe ser un número.',
            'price.min' => 'El precio no puede ser negativo.',
            'currency.required_with' => 'La moneda es obligatoria cuando se especifica el precio.',
            'currency.size' => 'La moneda debe ser un código de 3 letras (ej. USD, EUR).',
            'skills.required' => 'Las habilidades son obligatorias.',
            'skills.*.max' => 'Las habilidades no pueden tener más de 100 caracteres.',
            'level.required' => 'El nivel es obligatorio.',
            'level.in' => 'El nivel debe ser principiante, intermedio, avanzado o experto.',
            'language.required' => 'El idioma es obligatorio.',
            'language.max' => 'El idioma no puede tener más de 50 caracteres.',
        ];
    }
}
