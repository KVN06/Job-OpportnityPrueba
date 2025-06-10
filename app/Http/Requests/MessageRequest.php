<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'receiver_id' => ['required', 'integer', 'exists:users,id'],
            'subject' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:1', 'max:5000'],
            'parent_id' => ['nullable', 'integer', 'exists:messages,id']
        ];
    }

    public function messages()
    {
        return [
            'receiver_id.required' => 'Debe seleccionar un destinatario.',
            'receiver_id.exists' => 'El destinatario seleccionado no existe.',
            'content.required' => 'El contenido del mensaje es obligatorio.',
            'content.min' => 'El mensaje debe tener al menos 1 carácter.',
            'content.max' => 'El mensaje no puede exceder los 5000 caracteres.',
            'subject.max' => 'El asunto no puede exceder los 255 caracteres.',
            'parent_id.exists' => 'El mensaje al que responde no existe.'
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('parent_id') && empty($this->input('parent_id'))) {
            $this->request->remove('parent_id');
        }
    }
}
