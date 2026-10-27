<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ProductSupplier extends Model
{
    protected $fillable = ['supplier_id', 'product_id'];
    protected $table = 'supplier'; 

    public function rules()
    {
        return [
            'supplier_id' => 'required|exists:supplier,id',
            'product_id' => 'required|exists:product,id',
        ];
    }

}
