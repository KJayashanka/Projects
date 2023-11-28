<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = 
    [
        'product_code', 
        'product_name', 
        'price', 
        'expiry_date',
        'issue_type_id'
    ];

    public function discount()
    {
        return $this->hasOne(Discount::class, 'p_id');
    }
    
    public function freeIssue()
    {
        return $this->belongsTo(FreeIssue::class, 'issue_type_id');
    }
}
