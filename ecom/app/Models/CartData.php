<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartData extends Model
{
    use HasFactory;
    public $timestamps = true;

    public $fillable = ['user_id', 'session_id'];

    public function items()
    {
        return $this->hasMany(CartItems::class);
    }

    public function getCart(Request $request)
    {
        $user = $request->user();
        $sessionId = $request->session()->getId();

        if ($user) {
            $cart = CartData::firstOrCreate(['user_id' => $user->id]);
        } else {
            $cart = CartData::firstOrCreate(['session_id' => $sessionId]);
        }

        return $cart;
    }

    public function removeItem(CartData $cart, $product_id)
    {
        $cart->items()->where('product_id', $product_id)->delete();
    }
}
