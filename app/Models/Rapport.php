<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rapport extends Model
{
    use HasFactory;

    protected $fillable = [
        'mission_id', 'user_id', 'deroulement',
        'difficultes', 'actions_realisees', 'fichiers',
        'fiche_passage_path', 'admin_notes',
    ];

    protected $casts = [
        'fichiers' => 'array',
    ];

    public function mission()
    {
        return $this->belongsTo(Mission::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
