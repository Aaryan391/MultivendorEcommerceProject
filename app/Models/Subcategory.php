<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'category_id', 'vendor_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
    /**
     * Get the products for the subcategory.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

