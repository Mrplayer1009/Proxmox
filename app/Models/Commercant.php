<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commercant extends Model
{
    protected $table = 'commercants';
    protected $primaryKey = 'id_commercant';
    protected $fillable = [
        'nom', 'email', 'telephone', 'adresse',
    ];

    public function produits()
    {
        return $this->hasMany(Produit::class, 'id_commercant', 'id_commercant');
    }

    public function contrats()
    {
        return $this->hasMany(Contrat::class, 'id_commercant', 'id_commercant');
    }
} 