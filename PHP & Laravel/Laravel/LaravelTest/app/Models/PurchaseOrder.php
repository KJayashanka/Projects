<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'po_number',
        'remark',
        'order_date',
        'zone',
        'region',
        'territory',
        'distributor',
        // Add more fields here
    ];

    // Define relationships if necessary
    public function purchaseOrderLines()
    {
        return $this->hasMany(PurchaseOrderLine::class);
    }
    public function distributorUser()
    {
        return $this->belongsTo(User::class, 'distributor', 'username');
    }
}
