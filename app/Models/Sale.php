<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    // FILLABLE FIELDS
    protected $fillable = [
        'customer_id',
        'user_id',
        'status',
        'payment_method',
        'down_payment',
        'notes',
        'total',
    ];

    // RELATIONSHIPS
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}