<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'order_item_id';

    protected $fillable = ['order_id', 'product_id', 'variation_id', 'quantity', 'total_price'];

    protected $casts = [
        'total_price' => 'decimal:2'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product(): BelongsTo
    {
        // Products model uses class name 'Products' in your codebase
        return $this->belongsTo(Products::class, 'product_id');
    }

    public function variation(): BelongsTo
    {
        return $this->belongsTo(Product_variation::class, 'variation_id', 'id');
    }
}