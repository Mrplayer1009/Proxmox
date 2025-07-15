<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    protected $table = 'factures';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_prestataire',
        'mois',
        'date_generation',
        'montant',
        'pdf_path',
    ];
    public $timestamps = false;

    public function prestataire()
    {
        return $this->belongsTo(Prestataire::class, 'id_prestataire', 'id_prestataire');
    }
} 