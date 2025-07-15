<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contrat extends Model
{
    protected $table = 'contrats';
    protected $primaryKey = 'id_contrat';
    protected $fillable = [
        'id_commercant', 'date_debut', 'date_fin', 'statut', 'fichier_pdf',
    ];

    public function commercant()
    {
        return $this->belongsTo(Commercant::class, 'id_commercant', 'id_commercant');
    }
} 