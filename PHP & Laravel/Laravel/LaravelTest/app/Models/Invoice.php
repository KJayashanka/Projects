<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'invoice_id',
        'invoice_number',
        'po_number',
        'distributor_id',
        'invoice_date',
    ];

    public function distributor()
    {
        return $this->belongsTo(User::class, 'distributor_id');
    }
}
