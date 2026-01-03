<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'cart';
    protected $primaryKey = 'cart_id';

    protected $fillable = ['u_id', 'p_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'u_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'p_id');
    }
}
