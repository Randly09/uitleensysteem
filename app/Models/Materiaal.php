<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materiaal extends Model
{
    protected $fillable = [
        'naam',
        'hoeveelheid',
        'lokaal',
        'conditie',
            'beschikbaarheid',
        'opmerkingen',
        'foto_path',
    ];
}