<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'package_id',
        'package_code',
        'package_name',
        'price_per_kg',
        'estimated_hours',
        'priority_level',
        'weight',
        'total_price',
        'driver_id',
        'driver_item_count',
        'driver_notes',
        'picked_up_at',
        'dropped_at',
        'operator_id',
        'operator_item_count',
        'item_discrepancy',
        'discrepancy_note',
        'status',
        'delay_reason',
        'delay_minutes',
        'delayed_at',
        'estimated_completion_at',
        'actual_completion_at',
        'delivered_at',
        'customer_notes',
        'payment_status',
    ];

    protected function casts(): array
    {
        return [
            'picked_up_at' => 'datetime',
            'dropped_at' => 'datetime',
            'delayed_at' => 'datetime',
            'estimated_completion_at' => 'datetime',
            'actual_completion_at' => 'datetime',
            'delivered_at' => 'datetime',
            'price_per_kg' => 'decimal:2',
            'weight' => 'decimal:2',
            'total_price' => 'decimal:2',
            'driver_item_count' => 'integer',
            'operator_item_count' => 'integer',
            'item_discrepancy' => 'integer',
            'delay_minutes' => 'integer',
            'priority_level' => 'integer',
            'estimated_hours' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'order_id')->latest();
    }

    // Check if there is discrepancy between Driver input and Operator recount
    public function hasDiscrepancy(): bool
    {
        return $this->driver_item_count !== null && 
               $this->operator_item_count !== null && 
               $this->item_discrepancy !== 0;
    }

    public function getHasDiscrepancyAttribute(): bool
    {
        return $this->hasDiscrepancy();
    }

    // Priority ordering: Prioritas (4) > VVIP (3) > VIP (2) > Reguler (1)
    public function scopePrioritized($query)
    {
        return $query->orderBy('priority_level', 'desc')->orderBy('created_at', 'asc');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'menunggu_penjemputan' => 'Menunggu Penjemputan',
            'telah_diambil' => 'Telah Diambil Driver',
            'telah_drop' => 'Telah Drop di Laundry',
            'siap_dikerjakan' => 'Siap Dikerjakan',
            'proses' => 'Sedang Diproses',
            'pending' => 'Tertunda (Pending)',
            'siap_diantar' => 'Selesai / Siap Diantar',
            'dalam_pengantaran' => 'Dalam Pengantaran',
            'telah_diantar' => 'Telah Diantar (Selesai)',
            default => ucfirst(str_replace('_', ' ', $this->status))
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'menunggu_penjemputan' => 'bg-amber-100 text-amber-800 border-amber-300',
            'telah_diambil' => 'bg-blue-100 text-blue-800 border-blue-300',
            'telah_drop' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            'siap_dikerjakan' => 'bg-cyan-100 text-cyan-800 border-cyan-300',
            'proses' => 'bg-purple-100 text-purple-800 border-purple-300',
            'pending' => 'bg-rose-100 text-rose-800 border-rose-300 animate-pulse',
            'siap_diantar' => 'bg-teal-100 text-teal-800 border-teal-300',
            'dalam_pengantaran' => 'bg-sky-100 text-sky-800 border-sky-300',
            'telah_diantar' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300'
        };
    }

    public function getPriorityBadgeClassAttribute(): string
    {
        return match($this->package_code) {
            'prioritas' => 'bg-gradient-to-r from-red-500 to-rose-600 text-white font-bold shadow-sm',
            'vvip' => 'bg-gradient-to-r from-purple-500 to-indigo-600 text-white font-bold shadow-sm',
            'vip' => 'bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold shadow-sm',
            'reguler' => 'bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold shadow-sm',
            default => 'bg-gray-500 text-white'
        };
    }

    public function recalculatePrediction(): void
    {
        $baseHours = $this->estimated_hours ?: 24;
        $startTime = $this->dropped_at ?: $this->picked_up_at ?: $this->created_at ?: now();
        $predictedTime = Carbon::parse($startTime)->addHours($baseHours);

        if ($this->delay_minutes > 0) {
            $predictedTime->addMinutes($this->delay_minutes);
        }

        $this->estimated_completion_at = $predictedTime;
    }
}

