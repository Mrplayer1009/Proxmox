<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $table = 'evaluations';
    protected $primaryKey = 'id_evaluation';
    protected $fillable = [
        'id_prestataire',
        'id_client',
        'note',
        'commentaire',
    ];
    public $timestamps = true;

    public function prestataire()
    {
        return $this->belongsTo(Prestataire::class, 'id_prestataire', 'id_prestataire');
    }

    public function client()
    {
        return $this->belongsTo(Utilisateur::class, 'id_client', 'id_utilisateur');
    }
} 