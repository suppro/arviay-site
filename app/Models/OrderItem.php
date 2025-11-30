<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'OrderItem';
    
    protected $fillable = [
        'order_id', 'product_variant_id', 'quantity', 'price_at_moment'
    ];
    
    public $timestamps = false;
    
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
    
    public function product()
    {
        return $this->hasOneThrough(
            Product::class,
            ProductVariant::class,
            'id', // Foreign key on ProductVariant table
            'id', // Foreign key on Product table
            'product_variant_id', // Local key on OrderItem table
            'product_id' // Local key on ProductVariant table
        );
    }
}