<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Product, Category, Supplier with proper relationships (Product belongsTo Category, Product belongsToMany Supplier) 
class Category extends Model
{
    protected $fillable = [
        'name',
        'parent_id'
    ];
    
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ];
    }

    //
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
}
