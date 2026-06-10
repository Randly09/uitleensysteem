<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logboek extends Model
{
    protected $fillable = [
        'user_id',
        'item_type',
        'materiaal_id',
        'materiaal_set_id',
        'item_naam',
        'inleverdatum',
        'hoeveelheid',
        'conditie',
        'terug',
    ];
}