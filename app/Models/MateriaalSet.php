<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MateriaalSet extends Model
{
protected $fillable = [
    'naam',
    'hoeveelheid',
    'omschrijving',
];
    public function materialen()
    {
        return $this->belongsToMany(Materiaal::class, 'materiaal_materiaal_set')
            ->withPivot('aantal')
            ->withTimestamps();
    }
}