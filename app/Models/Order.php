<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'total',
        'payment_proof',
        'tracking_number',
        'shipping_service',
        'order_date',
        'payment_date',
        'packaging_date',
        'shipping_date',
        'completed_date',
        'canceled_date',
        'discount',
        'final_total',
        'promo_code',
        'shipping_cost',
        'total_weight',
        'origin_location',
        'destination_location',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
