<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'utilisateur';
    protected $primaryKey = 'id_utilisateur';
    public $timestamps = false;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'telephone',
        'adresse',
        'type_utilisateur',
        'date_inscription',
        'statut_compte',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
} 