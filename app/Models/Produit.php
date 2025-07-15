<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $table = 'produits';
    protected $primaryKey = 'id_produits';

    protected $fillable = [
        'id_commercant',
        'id_utilisateur',
        'nom',
        'description',
        'prix',
        'quantite',
        'image_url',
    ];

    public $timestamps = false;
    
    const CREATED_AT = 'date_creation';
    const UPDATED_AT = 'date_mise_a_jour';

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur', 'id_utilisateur');
    }

    public function commercant()
    {
        return $this->belongsTo(Commercant::class, 'id_commercant', 'id_commercant');
    }
}
