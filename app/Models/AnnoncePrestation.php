<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnoncePrestation extends Model
{
    use HasFactory;
    protected $table = 'annonce_prestation';
    protected $primaryKey = 'id_annonce_prestation';
    protected $fillable = [
        'id_prestataire',
        'titre',
        'description',
        'prix',
        'statut',
    ];

    public function prestation()
    {
        return $this->belongsTo(Prestation::class, 'id_prestation', 'id_prestation');
    }
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur', 'id_utilisateur');
    }
    public function prestataire()
    {
        return $this->belongsTo(Prestataire::class, 'id_prestataire', 'id_prestataire');
    }
} 