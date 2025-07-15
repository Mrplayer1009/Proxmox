<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $table = 'paiement';
    protected $primaryKey = 'id_paiement';
    public $timestamps = false;
    protected $fillable = [
        'id_utilisateur',
        'montant',
        'date',
        'methode',
        'statut',
        'info',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur', 'id_utilisateur');
    }
} 