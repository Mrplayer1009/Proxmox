<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestataire extends Model
{
    protected $table = 'prestataire';
    protected $primaryKey = 'id_prestataire';
    protected $fillable = [
        'id_utilisateur',
        'nom_entreprise',
        'siret',
        'adresse',
        'telephone',
        'statut_validation', // enum: en_attente, validé, refusé
    ];
    public $timestamps = true;

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'id_prestataire', 'id_prestataire');
    }
} 