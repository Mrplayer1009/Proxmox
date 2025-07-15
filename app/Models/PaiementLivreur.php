<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaiementLivreur extends Model
{
    protected $table = 'paiement_livreur';
    protected $primaryKey = 'id_paiement';
    public $timestamps = true;
    const CREATED_AT = 'cree_le';
    const UPDATED_AT = 'modifie_le';
    protected $fillable = [
        'id_livreur',
        'montant',
        'date_paiement',
        'methode_paiement',
        'statut_paiement',
    ];

    public function livreur()
    {
        return $this->belongsTo(Livreur::class, 'id_livreur', 'id_livreur');
    }
} 