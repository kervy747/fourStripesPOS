<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code',
        'name',
        'category',
        'unit',
        'quantity',
        'standard_level',
        'unit_cost',
        'price',
        'weight',
        'warranty_months',
        'description',
    ];

    public function getStatusAttribute()
    {
        if ($this->quantity == 0) {
            return 'out_of_stock';
        }

        if ($this->quantity <= $this->standard_level) {
            return 'low_stock';
        }

        return 'in_stock';
    }
}