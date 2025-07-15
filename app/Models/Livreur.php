<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Livreur extends Model
{
    protected $table = 'livreur';
    protected $primaryKey = 'id_livreur';
    public $timestamps = true;
    protected $fillable = [
        'id_utilisateur',
        'pieces_justificatives',
        'note_moyenne',
        'solde_portefeuille',
        'statut_validation',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur', 'id_utilisateur');
    }
} 