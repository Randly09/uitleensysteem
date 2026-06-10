<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retour extends Model
{
    protected $table = 'retours';

    protected $fillable = [
        'naam',
        'materiaal_id',
        'retour_datum',
        'is_returned',
];

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'retours_users',
            'retours_id',
            'users_id'
        );
    }
    public function materiaal()
    {
    return $this->belongsTo(Materiaal::class);
    }
}