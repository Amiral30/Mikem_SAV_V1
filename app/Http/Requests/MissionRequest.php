<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MissionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'adresse' => 'required|string|max:500',
            'type_mission' => 'required|string|max:255',
            'prix_deplacement' => 'nullable|numeric|min:0',
            'date_mission' => 'required|date',
            'chef_equipe_id' => 'nullable|exists:users,id',
            'techniciens' => 'required|array|min:1',
            'techniciens.*' => 'exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required' => 'Le titre est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'adresse.required' => 'L\'adresse est obligatoire.',
            'type_mission.required' => 'Le type de mission est obligatoire.',
            'date_mission.required' => 'La date est obligatoire.',
            'techniciens.required' => 'Veuillez sélectionner au moins un technicien.',
        ];
    }
}
