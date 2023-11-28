<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'order_number',
        'product_name',
        'product_code',
        'price',
        'purchase_quantity',
        'free_quantity',
        'amount',
        'order_date',
        'order_time',
        'discount',
        'net_amount',
    ];
}
