<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestation extends Model
{
    protected $table = 'prestations';
    protected $primaryKey = 'id_prestation';
    protected $fillable = [
        'id_prestataire',
        'nom',
        'habilitation',
        'tarif',
    ];
    public $timestamps = true;

    public function prestataire()
    {
        return $this->belongsTo(Prestataire::class, 'id_prestataire', 'id_prestataire');
    }
} 