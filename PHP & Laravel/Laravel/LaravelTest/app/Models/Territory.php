<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Territory extends Model
{
    protected $fillable = [
        'zone_id',
        'region_id',
        'territory_code',
        'territory_name',
        // Add other attributes here
    ];

    // Define relationships
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
    public function distributor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    // Add any additional methods or scopes as needed
}
