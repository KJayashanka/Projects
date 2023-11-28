<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderLine extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'sku_code',	
        'sku_name',
        'distributor_price',	
        'mrp',	
        'units',
        'total_price',	
        // Add more fields here
    ];

    // Define relationships if necessary
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
