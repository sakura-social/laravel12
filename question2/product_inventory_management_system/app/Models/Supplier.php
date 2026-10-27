<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name',
    ];
    protected $table = 'suppliers'; 

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }
}
