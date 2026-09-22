<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;

    // FILLABLE FIELDS
    protected $fillable = [
        'sale_id',
        'product_id',
        'item_code',
        'item_name',
        'quantity',
        'price',
        'subtotal',
    ];

    // RELATIONSHIPS
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}