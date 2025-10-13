<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Supplier extends Model
{

   
    protected $table = 'supplier';
    
    protected $fillable = [
    'supplier_name', 
    'pic_supplier',
    'supplier_email', 
    'supplier_phone', 
    'supplier_address'];



    
    public function get_supplier()
    {
        // get all supplier
        $sql = $this->select("*");
        return $sql;
    }
}
