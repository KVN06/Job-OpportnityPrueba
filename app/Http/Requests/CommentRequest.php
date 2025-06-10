<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommentRequest extends FormRequest
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
            'content' => ['required', 'string', 'min:1', 'max:1000'],
            'commentable_type' => [
                'required',
                'string',
                Rule::in(['App\Models\JobOffer', 'App\Models\Training', 'App\Models\Portfolio'])
            ],
            'commentable_id' => ['required', 'integer', 'min:1'],
            'parent_id' => ['nullable', 'integer', 'exists:comments,id'],
        ];

        // Si es una actualización, solo permitimos modificar el contenido
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return [
                'content' => $rules['content']
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
            'content.required' => 'El contenido del comentario es obligatorio.',
            'content.min' => 'El comentario debe tener al menos 1 carácter.',
            'content.max' => 'El comentario no puede tener más de 1000 caracteres.',
            'commentable_type.required' => 'El tipo de elemento a comentar es obligatorio.',
            'commentable_type.in' => 'El tipo de elemento a comentar no es válido.',
            'commentable_id.required' => 'El ID del elemento a comentar es obligatorio.',
            'commentable_id.integer' => 'El ID del elemento a comentar debe ser un número.',
            'commentable_id.min' => 'El ID del elemento a comentar no es válido.',
            'parent_id.integer' => 'El ID del comentario padre debe ser un número.',
            'parent_id.exists' => 'El comentario padre especificado no existe.',
        ];
    }
}
