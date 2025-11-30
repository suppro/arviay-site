<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'Product';
    
    protected $fillable = [
        'category_id', 'name', 'description', 'image', 'is_active'
    ];
    
    public $timestamps = false;
    
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    
    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }
}