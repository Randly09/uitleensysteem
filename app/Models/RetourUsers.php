<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetourUsers extends Model
{
    protected $table = 'retours_users';

    protected $fillable = [
        'retours_id',
        'users_id',
    ];

    public $timestamps = false;

    public function retour()
    {
        return $this->belongsTo(Retour::class, 'retours_id');
    }

    public function user()
    {
        return $this->belongsTo(Users::class, 'users_id');
    }
}