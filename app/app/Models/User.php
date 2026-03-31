<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'telephone',
        'disponible',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'disponible' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTechnicien(): bool
    {
        return $this->role === 'technicien';
    }

    public function missions()
    {
        return $this->belongsToMany(Mission::class, 'mission_user')
            ->withPivot('is_chef_equipe', 'accepted')
            ->withTimestamps();
    }

    public function missionsEnChef()
    {
        return $this->hasMany(Mission::class, 'chef_equipe_id');
    }

    public function rapports()
    {
        return $this->hasMany(Rapport::class);
    }

    public function scopeTechniciens($query)
    {
        return $query->where('role', 'technicien');
    }

    public function scopeDisponibles($query)
    {
        return $query->where('disponible', true);
    }
}
