<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_name',
        'role',
        'order_id',
        'order_code',
        'action',
        'title',
        'description',
        'ip_address',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Record an audit log entry for Owner & Admin monitoring
     */
    public static function log(?User $user, string $action, string $title, string $description, ?Order $order = null, array $metadata = []): self
    {
        return self::create([
            'user_id' => $user?->id,
            'user_name' => $user ? $user->name : 'Sistem / Tamu',
            'role' => $user ? $user->role : 'system',
            'order_id' => $order?->id,
            'order_code' => $order?->order_code,
            'action' => $action,
            'title' => $title,
            'description' => $description,
            'ip_address' => request()->ip(),
            'metadata' => $metadata,
        ]);
    }

    public function getRoleBadgeClassAttribute(): string
    {
        return match($this->role) {
            'owner' => 'bg-amber-100 text-amber-800 border-amber-300',
            'admin' => 'bg-blue-100 text-blue-800 border-blue-300',
            'supir' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'oprator' => 'bg-purple-100 text-purple-800 border-purple-300',
            'customer' => 'bg-teal-100 text-teal-800 border-teal-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300'
        };
    }
}

