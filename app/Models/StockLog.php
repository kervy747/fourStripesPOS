<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    use HasFactory;

    // FILLABLE FIELDS
    protected $fillable = [
        'product_id',
        'quantity_added',
        'notes',
    ];

    // RELATIONSHIPS
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}