<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Planning extends Model
{
    protected $table = 'planning';
    protected $primaryKey = 'id_planning';
    protected $fillable = [
        'id_livreur',
        'date',
        'lieu_arrivee', // id de l'adresse
        'description',
    ];

    public function adresseArrivee()
    {
        return $this->belongsTo(Addresse::class, 'lieu_arrivee');
    }
}
