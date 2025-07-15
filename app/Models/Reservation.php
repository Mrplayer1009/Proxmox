<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservations';
    protected $primaryKey = 'id_reservation';
    protected $fillable = [
        'id_prestation',
        'id_client',
        'date',
        'heure_debut',
        'heure_fin',
        'statut',
    ];
    public $timestamps = true;

    public function prestation()
    {
        return $this->belongsTo(Prestation::class, 'id_prestation', 'id_prestation');
    }

    public function client()
    {
        return $this->belongsTo(Utilisateur::class, 'id_client', 'id_utilisateur');
    }
} 