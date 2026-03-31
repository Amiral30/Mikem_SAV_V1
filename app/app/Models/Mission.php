<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'adresse',
        'type_mission',
        'prix_deplacement',
        'date_mission',
        'statut',
        'is_groupe',
        'chef_equipe_id',
        'created_by',
    ];

    protected $casts = [
        'date_mission' => 'date',
        'prix_deplacement' => 'decimal:2',
        'is_groupe' => 'boolean',
    ];

    const STATUTS = [
        'en_attente' => 'En attente',
        'en_cours' => 'En cours',
        'en_pause' => 'En pause',
        'suspendue' => 'Suspendue',
        'terminee' => 'Terminée',
    ];

    const STATUT_COLORS = [
        'en_attente' => '#ffc107',
        'en_cours' => '#2196f3',
        'en_pause' => '#ff9800',
        'suspendue' => '#f44336',
        'terminee' => '#4caf50',
    ];

    const STATUT_CLASSES = [
        'en_attente' => 'badge-warning',
        'en_cours' => 'badge-info',
        'en_pause' => 'badge-pause',
        'suspendue' => 'badge-danger',
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
