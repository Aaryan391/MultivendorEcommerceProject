<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorRequest extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'status','pan_number','phone_number'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
