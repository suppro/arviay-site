<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'path',
        'is_main',
        'sort_order',
    ];
    
    protected $casts = [
        'is_main' => 'boolean',
        'sort_order' => 'integer',
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
    /**
     * Получить URL изображения
     */
    public function getUrlAttribute(): string
    {
        // Если путь уже содержит /storage/, используем его как есть
        if (str_starts_with($this->path, '/storage/') || str_starts_with($this->path, 'storage/')) {
            return asset($this->path);
        }
        
        // Иначе добавляем storage/
        return asset('storage/' . $this->path);
    }
}

