<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable //implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relations Eloquent personnalisées pour les ressources de l'utilisateur
     */
    public function notes()
    {
        return $this->hasMany(\App\Models\Note::class, 'owner_id');
    }
    public function folders()
    {
        return $this->hasMany(\App\Models\Folder::class, 'owner_id');
    }
    public function tasks()
    {
        return $this->hasMany(\App\Models\Task::class, 'owner_id');
    }
    public function projets()
    {
        return $this->hasMany(\App\Models\Projet::class, 'owner_id');
    }
}
