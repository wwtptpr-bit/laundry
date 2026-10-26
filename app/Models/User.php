<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSupir(): bool
    {
        return $this->role === 'supir';
    }

    public function isOperator(): bool
    {
        return $this->role === 'oprator';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function driverOrders()
    {
        return $this->hasMany(Order::class, 'driver_id');
    }

    public function operatorOrders()
    {
        return $this->hasMany(Order::class, 'operator_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'owner' => 'Owner (Pemilik)',
            'admin' => 'Admin (Monitoring)',
            'supir' => 'Supir (Driver Antar-Jemput)',
            'oprator' => 'Operator Laundry',
            'customer' => 'Customer / Pelanggan',
            default => ucfirst($this->role)
        };
    }
}
