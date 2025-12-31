<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Payment extends Model
{
    use HasFactory;

    public const METHOD_CASH = 'cash';
    public const METHOD_CARD = 'card';
    public const METHOD_MOBILE = 'mobile';

    public const STATUS_PENDING = 'pending';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'order_id',
        'amount',
        'method',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Scopes
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Optimized scope for filtering payments by user.
     * Uses EXISTS subquery which is more efficient than whereHas for large datasets.
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->whereExists(function ($subquery) use ($userId) {
            $subquery->select(DB::raw(1))
                ->from('orders')
                ->whereColumn('orders.id', 'payments.order_id')
                ->where('orders.user_id', $userId);
        });
    }

    /**
     * Date range scope for reporting.
     */
    public function scopeDateRange(Builder $query, $start, $end): Builder
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }

    // Helpers
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }
}
