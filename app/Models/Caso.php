<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caso extends Model
{
    use HasFactory;

    protected $fillable = [
        'hora',
        'fecha',
        'desglose',
        'cantidad',
        'id_doctor',
        'id_reception',
    ];
}
