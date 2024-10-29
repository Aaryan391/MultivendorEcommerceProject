<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'order_status',
        'payment_status',
        'shipping_status',
        'order_payment_type',
        'order_total',
        'customer_name',
        'customer_address',
        'customer_phone_number',
        'customer_town_city',
        'customer_note',
        'customer_company',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with the OrderDetail model
    public function orderdetails()
    {
        return $this->hasMany(OrderDetails::class);
    }
}
