<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use Hasfactory;
    protected $table = 'wishlist';
    protected $primaryKey = 'id';

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
