<?php

namespace App\Models;

use App\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nic',
        'address',
        'mobile',
        'email',
        'gender',
        'territory_id',
        'username',
        'password',
    ];

    // Define relationships if needed
    public function territory()
    {
        return $this->belongsTo(Territory::class, 'territory_id');
    }
    public function PurchaseOrder(){
        return $this->belongsTo(PurchaseOrder::class,'username');
    }
}
