<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    // Zone.php
    public function regions()
    {
        return $this->hasMany(Region::class, 'zone_id');
    }

    protected $fillable = [
        'long_description',
        'short_description',
        'zone_code',
    ];
}
