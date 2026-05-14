<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['cart_id', 'product_id', 'quantity'];

    // ─── Many-to-One: CartItem → Cart ────────────────────────────────────────
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    // ─── Many-to-One: CartItem → Product ─────────────────────────────────────
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
