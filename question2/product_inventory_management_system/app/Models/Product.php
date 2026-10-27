<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'soft_delete',
        'stock_quantity'
    ];

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'soft_delete' => 'number|in:0,1',
            'stock_quantity' => 'required|integer|min:0',
        ];
    }

    // Product, Category, Supplier with proper relationships (Product belongsTo Category, Product belongsToMany Supplier) 

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class);
    }

    //accesors for price 
    protected function formatPrice(): Attribute
    {
        return Attribute::make(
            get: fn($value) => number_format($value, 2),
        );
    }

    //mutator for name 
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => ucfirst($value),
        );
    }  

    protected function categoryName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->category ? $this->category->name : null,
        );
    }

}
