<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RapportRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'deroulement' => 'required|string',
            'difficultes' => 'nullable|string',
            'actions_realisees' => 'required|string',
            'fichiers' => 'nullable|array|max:10',
            'fichiers.*' => 'file|max:2048|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx',
        ];
    }

    public function messages(): array
    {
        return [
            'deroulement.required' => 'Le déroulement est obligatoire.',
            'actions_realisees.required' => 'Les actions réalisées sont obligatoires.',
            'fichiers.max' => 'Vous ne pouvez pas envoyer plus de 10 fichiers (images ou documents).',
            'fichiers.*.max' => 'Chaque fichier ne doit pas dépasser 2 Mo.',
        ];
    }
}
