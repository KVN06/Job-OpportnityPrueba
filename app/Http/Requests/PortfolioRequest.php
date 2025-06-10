<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PortfolioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->type === 'unemployed';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url_proyect' => 'required|url|max:255',
            'url_pdf' => 'nullable|file|mimes:pdf|max:2048',
            'technologies' => 'nullable|array',
            'technologies.*' => 'required|string|max:50',
            'status' => 'nullable|string|in:draft,published,archived'
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            // Make some fields optional on update
            $rules['url_proyect'] = 'nullable|url|max:255';
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
            'title.max' => 'El título no puede tener más de 255 caracteres.',
            'url_proyect.required' => 'La URL del proyecto es obligatoria.',
            'url_proyect.url' => 'La URL del proyecto debe ser válida.',
            'url_pdf.mimes' => 'El archivo debe ser un PDF.',
            'url_pdf.max' => 'El archivo no puede pesar más de 2MB.',
            'technologies.*.max' => 'La tecnología no puede tener más de 50 caracteres.',
            'status.in' => 'El estado seleccionado no es válido.'
        ];
    }
}
