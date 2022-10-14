<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use App\Models\CartProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;
    public function products()
    {
        return $this->belongsToMany(Product::class)->using(CartProduct::class)->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
