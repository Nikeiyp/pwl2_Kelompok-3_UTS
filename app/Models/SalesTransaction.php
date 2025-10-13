<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesTransaction extends Model
{
    use HasFactory;

    protected $table = 'sales_transactions';

    protected $fillable = [
        'cashier_name',
        'customer_email',
        'grand_total', // Tambahkan ini
    ];

    public function details()
    {
        return $this->hasMany(SalesTransactionDetail::class, 'sales_transaction_id');
    }
}