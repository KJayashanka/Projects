<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;
    // Region.php
    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }


    // Define the fillable attributes
    protected $fillable = ['zone_id', 'region_code', 'region_name'];

    // Define relationships or other methods as needed
}

