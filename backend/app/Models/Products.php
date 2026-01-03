<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Products extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $primaryKey = 'id';

    protected $fillable = [
        'category_id',
        'name',
        'price',
        'description',
        'picture'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

   public function category(): BelongsTo
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
    public function variations()
{
    return $this->hasMany(Product_variation::class, 'product_id');
}

}