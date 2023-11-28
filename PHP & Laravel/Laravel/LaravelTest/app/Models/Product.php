<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'sku_code', 
        'sku_name', 
        'distributor_price', 
        'mrp', 
        'units', 
        'measure',
    ];
}

