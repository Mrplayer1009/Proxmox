<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Localisation extends Model
{
    protected $table = 'localisations';
    protected $primaryKey = 'id_localisation';
    public $timestamps = true;

    protected $fillable = [
        'nom',
        'ordre',
        'livraison_id',
        'cree_le',
        'modifie_le',
    ];

    const CREATED_AT = 'cree_le';
    const UPDATED_AT = 'modifie_le';

    public function livraison()
    {
        return $this->belongsTo(Livraison::class, 'livraison_id', 'id_livraison');
    }
} 