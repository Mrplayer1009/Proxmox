<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batiment extends Model
{
    protected $table = 'batiment';
    protected $fillable = ['nom', 'id_addresse'];

    public function addresse()
    {
        return $this->belongsTo(Addresse::class, 'id_addresse');
    }
} 