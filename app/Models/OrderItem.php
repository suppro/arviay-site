<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_sku',
        'price_at_purchase',
        'quantity',
    ];
    
    protected $casts = [
        'price_at_purchase' => 'decimal:2',
        'quantity' => 'integer',
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
    // Получить общую стоимость позиции
    public function getTotalAttribute()
    {
        return $this->price_at_purchase * $this->quantity;
    }
}