<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'description',
        'technical_specs',
        'price',
        'stock_quantity',
        'is_active',
    ];
    
    protected $casts = [
        'technical_specs' => 'array',
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'is_active' => 'boolean',
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id')->orderBy('sort_order');
    }
    
    public function mainImage()
    {
        return $this->hasOne(ProductImage::class, 'product_id')->where('is_main', true);
    }
    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }
    
    // Автоматическое создание slug при сохранении
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
        
        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }
    
    // Scope для активных товаров
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    // Scope для товаров в наличии
    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }
}