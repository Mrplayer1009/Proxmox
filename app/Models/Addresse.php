<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Addresse extends Model
{
    protected $table = 'addresse';
    protected $fillable = [
        'id_utilisateur',
        'rue',
        'ville',
        'code_postal',
    ];
    protected $primaryKey = 'id';
} 