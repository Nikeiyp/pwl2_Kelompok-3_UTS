<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category_product extends Model
{
    use HasFactory;
    protected $table = 'category_product';

    protected $fillable = [
        'product_category_name',
    ];

    public function get_category_product()
    {
        $sql = $this->select("*");
        return $sql;
    }
}