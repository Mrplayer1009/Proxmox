<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;
    protected $table = 'annonce';
    protected $primaryKey = 'id_annonce';
    public $timestamps = false;
    protected $fillable = [
        'id_utilisateur',
        'titre',
        'id_addresse',
        'nombre',
        'poids',
        'fragile',
        'description',
        'prix',
        'statut',
        'date_limite',
        'type_colis',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur', 'id_utilisateur');
    }
} 