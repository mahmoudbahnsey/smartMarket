<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'location', 'phone', 'email',
        'address', 'city', 'is_active', 'manager_id',
    ];

    protected $casts = ['is_active' => 'boolean'];

    // ─── One-to-One: Branch ↔ User (manager) ─────────────────────────────────
    // Each branch has exactly one manager
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    // ─── One-to-Many: Branch → Inventories ───────────────────────────────────
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    // ─── One-to-Many: Branch → Orders ────────────────────────────────────────
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // ─── Many-to-Many: Branch ↔ Products (through inventory) ─────────────────
    public function products()
    {
        return $this->belongsToMany(Product::class, 'inventories')
                    ->withPivot('quantity', 'low_stock_alert')
                    ->withTimestamps();
    }
}
