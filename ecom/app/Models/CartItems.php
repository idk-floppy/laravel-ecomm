<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartItems extends Model
{
    use HasFactory;
    public $timestamps = true;

    public $guarded = [];

    // Relations
    public function cart()
    {
        return $this->belongsTo(CartData::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Attributes
    public function getSubtotalAttribute()
    {
        $productData = json_decode($this->product_data);
        $price = $productData->price;

        return $this->qty * $price;
    }
}
