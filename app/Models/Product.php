<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\CartProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'price',

    ];

    public function carts()
    {
        return $this->belongsToMany(Cart::class)->using(CartProduct::class)->withTimestamps();
    }
}
