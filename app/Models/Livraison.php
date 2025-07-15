<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Livraison extends Model
{
    protected $table = 'livraison';
    protected $primaryKey = 'id_livraison';
    public $timestamps = false;
    protected $fillable = [
        'id_annonce',
        'id_livreur',
        'id_utilisateur',
        'id_adresse_depart',
        'id_adresse_arrivee',
        'date_livraison',
        'code_validation',
        'poids',
        'fragile',
        'statut',
        'contenu',
        'date',
        'modalite',
        'type',
    ];

    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'id_annonce', 'id_annonce');
    }

    public function livreur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_livreur', 'id_utilisateur');
    }


    public function localisations()
    {
        return $this->hasMany(\App\Models\Localisation::class, 'livraison_id', 'id_livraison');
    }

    public function adresseArrivee()
    {
        return $this->belongsTo(Addresse::class, 'id_adresse_arrivee');
    }

    public function adresseDepart()
    {
        return $this->belongsTo(Addresse::class, 'id_adresse_depart', 'id');
    }
}
