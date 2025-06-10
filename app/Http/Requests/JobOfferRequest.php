<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\JobOffer;

class JobOfferRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'description' => ['required', 'string', 'min:50'],
            'salary' => ['required', 'numeric', 'min:0'],
            'location' => ['required', 'string', 'max:255'],
            'geolocation' => ['nullable', 'string'],
            'offer_type' => ['required', Rule::in([JobOffer::TYPE_CONTRACT, JobOffer::TYPE_CLASSIFIED])],
            'experience_level' => ['required', Rule::in(['junior', 'medio', 'senior', 'lead'])],
            'contract_type' => ['required', Rule::in(['tiempo_completo', 'medio_tiempo', 'proyecto', 'practicas'])],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
            'required_skills' => ['nullable', 'array'],
            'required_skills.*' => ['string'],
            'benefits' => ['nullable', 'array'],
            'benefits.*' => ['string'],
            'application_deadline' => ['nullable', 'date', 'after:today'],
            'remote_work' => ['nullable', 'boolean']
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'El título de la oferta es obligatorio.',
            'title.min' => 'El título debe tener al menos 5 caracteres.',
            'description.required' => 'La descripción de la oferta es obligatoria.',
            'description.min' => 'La descripción debe tener al menos 50 caracteres.',
            'salary.required' => 'El salario es obligatorio.',
            'salary.numeric' => 'El salario debe ser un valor numérico.',
            'salary.min' => 'El salario no puede ser negativo.',
            'location.required' => 'La ubicación es obligatoria.',
            'offer_type.required' => 'El tipo de oferta es obligatorio.',
            'offer_type.in' => 'El tipo de oferta debe ser contrato o clasificado.',
            'experience_level.required' => 'El nivel de experiencia es obligatorio.',
            'experience_level.in' => 'El nivel de experiencia seleccionado no es válido.',
            'contract_type.required' => 'El tipo de contrato es obligatorio.',
            'contract_type.in' => 'El tipo de contrato seleccionado no es válido.',
            'categories.required' => 'Debe seleccionar al menos una categoría.',
            'categories.min' => 'Debe seleccionar al menos una categoría.',
            'application_deadline.after' => 'La fecha límite debe ser posterior a hoy.',
        ];
    }
}
