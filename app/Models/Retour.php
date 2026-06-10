<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retour extends Model
{
    protected $table = 'retours';

    protected $fillable = [
        'item_type',
        'materiaal_id',
        'materiaal_set_id',
        'item_naam',
        'aantal',
        'retour_datum',
        'is_returned',
    ];

    protected $casts = [
        'retour_datum' => 'datetime',
        'is_returned' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(
            Users::class,
            'retours_users',
            'retours_id',
            'users_id'
        )->withTimestamps();
    }

    public function materiaal()
    {
        return $this->belongsTo(Materiaal::class, 'materiaal_id');
    }

    public function set()
    {
        return $this->belongsTo(MateriaalSet::class, 'materiaal_set_id');
    }
}