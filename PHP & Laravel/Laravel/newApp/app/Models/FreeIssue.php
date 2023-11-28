<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreeIssue extends Model
{
    use HasFactory;

    protected $table = 'free_issues';

    protected $fillable = [
        'free_issue_label',
        'issue_type',
        'purchase_product',
        'free_product',
        'purchase_quantity',
        'free_quantity',
        'lower_limit',
        'upper_limit',
        'purchase_product_id',
    ];

    // Define relationships if needed
    public function purchaseProduct()
    {
        return $this->belongsTo(Product::class, 'purchase_product_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'issue_type_id');
    }
}

