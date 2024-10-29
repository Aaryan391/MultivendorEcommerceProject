<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'vendor_id'];

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
    /**
     * Get the products for the category.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
