<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $table = 'ProductVariant';
    
    protected $fillable = [
        'product_id', 'size_name', 'price'
    ];
    
    public $timestamps = false;
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_variant_id');
    }
}