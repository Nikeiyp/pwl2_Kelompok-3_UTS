<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category_product extends Model
{
    use HasFactory;
    
    // Specify the table name, although Eloquent handles this automatically by default
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