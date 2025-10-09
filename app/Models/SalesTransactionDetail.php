<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesTransactionDetail extends Model
{
    use HasFactory;

    protected $table = 'sales_transaction_details';

    protected $fillable = [
        'sales_transaction_id',
        'product_id',
        'quantity',
        'price',    // Tambahkan ini
        'subtotal', // Tambahkan ini
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}