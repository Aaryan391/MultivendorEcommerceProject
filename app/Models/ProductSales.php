<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSales extends Model
{
    use HasFactory;
    protected $table = 'product_sales';

    protected $fillable = [
        'user_name',
        'product_name',
        'vendor_name',
        'quantity',
        'price',
        'total_price',
        'commission',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
