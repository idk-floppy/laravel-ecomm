<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItems extends Model
{
    use HasFactory;
    public $timestamps = true;

    public $guarded = [];

    public function cart()
    {
        return $this->belongsTo(CartData::class);
    }

    public function getSubtotalAttribute()
    {
        $productData = json_decode($this->product_data);
        $price = $productData->price;

        return $this->qty * $price;
    }
}
