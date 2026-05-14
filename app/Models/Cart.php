<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    // ─── One-to-One: Cart ↔ User ──────────────────────────────────────────────
    // Each cart belongs to exactly one user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ─── One-to-Many: Cart → CartItems ───────────────────────────────────────
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    // ─── Helper ───────────────────────────────────────────────────────────────
    public function getTotalAttribute(): float
    {
        return $this->items->sum(fn($item) => $item->product->current_price * $item->quantity);
    }

    public function getCountAttribute(): int
    {
        return $this->items->sum('quantity');
    }
}
