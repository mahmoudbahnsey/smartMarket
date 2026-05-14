<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'user_id', 'branch_id',
        'subtotal', 'tax', 'total_price',
        'status', 'payment_status', 'payment_method',
        'shipping_address', 'shipping_city', 'notes',
    ];

    // ─── Many-to-One: Order → User ────────────────────────────────────────────
    // Many orders belong to one customer
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ─── Many-to-One: Order → Branch ─────────────────────────────────────────
    // Many orders belong to one branch
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // ─── One-to-Many: Order → OrderItems ─────────────────────────────────────
    // One order has many items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ─── Status Badge Helper ──────────────────────────────────────────────────
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'warning',
            'processing' => 'info',
            'shipped'    => 'primary',
            'delivered'  => 'success',
            'cancelled'  => 'danger',
            default      => 'secondary',
        };
    }

    // ─── Auto-generate order number ───────────────────────────────────────────
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            $order->order_number = 'ORD-' . strtoupper(uniqid());
        });
    }
}
