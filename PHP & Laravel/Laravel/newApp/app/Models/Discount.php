<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = 
    [
        'discount_label', 
        'purchase_product', 
        'discount_amount'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'p_id');
    }
}
