<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product_variation extends Model
{
    use HasFactory;
    protected $table = 'product_variation';
    protected $primaryKey = 'id';

    protected $fillable = ['product_id', 'size', 'color', 'stock'];

    public function product(): BelongsTo
    {
        // Products model uses class name 'Products' in your codebase
        return $this->belongsTo(Products::class, 'product_id');
    }
}