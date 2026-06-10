<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'user';

    protected $fillable = [
        'name',
        'Psnummer',
    ];

    public function retours()
    {
        return $this->belongsToMany(
            Retour::class,
            'retours_users',
            'users_id',
            'retours_id'
        )->withTimestamps();
    }
}