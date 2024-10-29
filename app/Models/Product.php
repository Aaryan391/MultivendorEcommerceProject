<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_name',
        'product_image',
        'product_description',
        'price',
        'stock',
        'category_id',
        'subcategory_id',
        'vendor_id',
        'brand',
        'color',
        'size',
        'material',
        'style',
        'tags',
        'popularity_score',
        'average_rating',
    ];
    protected $casts = [
        'tags' => 'array',
        'price' => 'decimal:2',
        'average_rating' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id')->where('role', 'vendor');
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class,);
    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
    public function isInWishlist($userId)
    {
        return $this->wishlists()->where('user_id', $userId)->exists();
    }
}
