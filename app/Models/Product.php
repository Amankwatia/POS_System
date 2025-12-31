<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'price',
        'stock',
        'reorder_level',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'reorder_level' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Status Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    // Stock Scopes
    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeOutOfStock(Builder $query): Builder
    {
        return $query->where('stock', '<=', 0);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->active()->inStock();
    }

    public function scopeLowStock(Builder $query): Builder
    {
        return $query->whereColumn('stock', '<=', 'reorder_level');
    }

    // Search Scope
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Select Scopes for optimization
    public function scopeBasicFields(Builder $query): Builder
    {
        return $query->select(['id', 'name', 'sku', 'price', 'stock', 'is_active']);
    }

    public function scopeForExport(Builder $query): Builder
    {
        return $query->select([
            'id', 'sku', 'name', 'description', 'price',
            'stock', 'reorder_level', 'is_active', 'created_at', 'updated_at'
        ]);
    }

    // Helpers
    public function isLowStock(): bool
    {
        return $this->stock <= $this->reorder_level;
    }

    public function hasStock(int $quantity = 1): bool
    {
        return $this->stock >= $quantity;
    }

    public function isOutOfStock(): bool
    {
        return $this->stock <= 0;
    }

    /**
     * Get stock status label.
     */
    public function getStockStatus(): string
    {
        if ($this->stock <= 0) {
            return 'Out of Stock';
        }
        if ($this->stock <= $this->reorder_level) {
            return 'Low Stock';
        }
        return 'In Stock';
    }
}
