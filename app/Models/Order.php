<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'total_amount',
        'payment_method',
        'delivery_method',
        'customer_details',
        'is_synced_to_1c',
    ];
    
    protected $casts = [
        'total_amount' => 'decimal:2',
        'customer_details' => 'array',
        'is_synced_to_1c' => 'boolean',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
    
    // Получить имя клиента из customer_details
    public function getCustomerNameAttribute()
    {
        return $this->customer_details['name'] ?? ($this->user ? $this->user->name : 'Гость');
    }
    
    // Получить телефон клиента из customer_details
    public function getCustomerPhoneAttribute()
    {
        return $this->customer_details['phone'] ?? ($this->user ? $this->user->phone : null);
    }
    
    // Получить адрес доставки из customer_details
    public function getDeliveryAddressAttribute()
    {
        return $this->customer_details['address'] ?? null;
    }
    
    // Проверка статуса
    public function isNew()
    {
        return $this->status === 'new';
    }
    
    public function isProcessing()
    {
        return $this->status === 'processing';
    }
    
    public function isCompleted()
    {
        return $this->status === 'completed';
    }
    
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }
}