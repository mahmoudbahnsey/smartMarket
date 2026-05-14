<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'branch_id', 'quantity', 'low_stock_alert'];

    // ─── Many-to-One: Inventory → Product ────────────────────────────────────
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // ─── Many-to-One: Inventory → Branch ─────────────────────────────────────
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // ─── Helper ───────────────────────────────────────────────────────────────
    public function isLowStock(): bool
    {
        return $this->quantity <= $this->low_stock_alert;
    }
}
