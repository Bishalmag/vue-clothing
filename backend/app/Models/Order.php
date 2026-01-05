<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = ['u_id', 'total_amount', 'status'];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
     public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'u_id');
    }
}
