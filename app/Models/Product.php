<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'price', 'sale_price',
        'image', 'is_featured', 'is_active', 'category_id',
    ];

    protected $casts = [
        'price'       => 'decimal:2',
        'sale_price'  => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active'   => 'boolean',
    ];

    // ─── Many-to-One: Product → Category ─────────────────────────────────────
    // Many products belong to one category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // ─── One-to-Many: Product → Inventories ──────────────────────────────────
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    // ─── One-to-Many: Product → OrderItems ───────────────────────────────────
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ─── One-to-Many: Product → Reviews ──────────────────────────────────────
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // ─── One-to-Many: Product → CartItems ────────────────────────────────────
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    // ─── Many-to-Many: Product ↔ Branches (through inventory) ────────────────
    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'inventories')
                    ->withPivot('quantity', 'low_stock_alert')
                    ->withTimestamps();
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────
    public function getCurrentPriceAttribute(): float
    {
        return $this->sale_price ?? $this->price;
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function getTotalStockAttribute(): int
    {
        return $this->inventories()->sum('quantity');
    }
}
