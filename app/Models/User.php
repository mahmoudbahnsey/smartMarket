<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'phone', 'address', 'avatar', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
    ];

    // ─── Role Helpers ────────────────────────────────────────────────────────
    public function isAdmin(): bool         { return $this->role === 'admin'; }
    public function isBranchManager(): bool { return $this->role === 'branch_manager'; }
    public function isCustomer(): bool      { return $this->role === 'customer'; }

    // ─── One-to-One: User (manager) ↔ Branch ─────────────────────────────────
    // One branch manager manages exactly one branch
    public function managedBranch()
    {
        return $this->hasOne(Branch::class, 'manager_id');
    }

    // ─── One-to-One: User ↔ Cart ─────────────────────────────────────────────
    // Each customer has exactly one cart
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    // ─── One-to-Many: User → Orders ──────────────────────────────────────────
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // ─── One-to-Many: User → Reviews ─────────────────────────────────────────
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
