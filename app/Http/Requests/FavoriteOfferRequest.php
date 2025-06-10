<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FavoriteOfferRequest extends FormRequest
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
        return [
            'notes' => 'nullable|string|max:500',
            'notification_preferences' => 'nullable|array',
            'notification_preferences.status_changes' => 'nullable|boolean',
            'notification_preferences.deadline_reminders' => 'nullable|boolean',
            'notification_preferences.similar_offers' => 'nullable|boolean'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'notes.max' => 'Las notas no pueden exceder los 500 caracteres.',
            'notification_preferences.array' => 'Las preferencias de notificación deben ser un conjunto de opciones.',
            'notification_preferences.*.boolean' => 'Las opciones de notificación deben ser verdadero o falso.'
        ];
    }
}
