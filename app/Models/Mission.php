<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre', 'description', 'adresse', 'type_mission',
        'prix_deplacement', 'date_mission', 'statut',
        'is_groupe', 'chef_equipe_id', 'created_by',
        'started_at', 'work_finished_at', 'submitted_at', 'validated_at',
    ];

    protected $casts = [
        'date_mission' => 'date',
        'prix_deplacement' => 'decimal:2',
        'is_groupe' => 'boolean',
        'started_at' => 'datetime',
        'work_finished_at' => 'datetime',
        'submitted_at' => 'datetime',
        'validated_at' => 'datetime',
    ];

    const STATUTS = [
        'en_attente' => 'En attente',
        'en_cours' => 'En cours',
        'en_pause' => 'En pause',
        'suspendue' => 'Suspendue',
        'soumis' => 'Rapport Soumis',
        'a_modifier' => 'À corriger',
        'terminee' => 'Terminée',
    ];

    const STATUT_COLORS = [
        'en_attente' => '#ffc107',
        'en_cours' => '#2196f3',
        'en_pause' => '#ff9800',
        'suspendue' => '#6c757d',
        'soumis' => '#9c27b0', // Violet pour soumis
        'a_modifier' => '#f44336', // Rouge pour corrections
        'terminee' => '#4caf50',
    ];

    const STATUT_CLASSES = [
        'en_attente' => 'badge-warning',
        'en_cours' => 'badge-info',
        'en_pause' => 'badge-pause',
        'suspendue' => 'badge-secondary',
        'soumis' => 'badge-purple',
        'a_modifier' => 'badge-danger',
        'terminee' => 'badge-success',
    ];

    public function techniciens()
    {
        return $this->belongsToMany(User::class, 'mission_user')
            ->withPivot('is_chef_equipe', 'accepted')
            ->withTimestamps();
    }

    public function chefEquipe()
    {
        return $this->belongsTo(User::class, 'chef_equipe_id');
    }

    public function createur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function rapports()
    {
        return $this->hasMany(Rapport::class);
    }

    public function getStatutLabelAttribute(): string
    {
        return self::STATUTS[$this->statut] ?? $this->statut;
    }

    public function getStatutColorAttribute(): string
    {
        return self::STATUT_COLORS[$this->statut] ?? '#6c757d';
    }

    public function getStatutClassAttribute(): string
    {
        return self::STATUT_CLASSES[$this->statut] ?? 'badge-secondary';
    }
}
