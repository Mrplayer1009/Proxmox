<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Abonnement extends Model
{
    protected $table = 'abonnement';
    protected $primaryKey = 'id_abonnement';
    public $timestamps = true;
    protected $fillable = [
        'id_utilisateur',
        'nom',
        'date_debut',
        'date_fin',
        'statut',
        'prix',
    ];
} 