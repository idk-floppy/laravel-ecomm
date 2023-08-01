<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class CartData extends Model
{
    use HasFactory;
    public $timestamps = true;

    public $fillable = ['user_id', 'session_id'];

    public function items()
    {
        return $this->hasMany(CartItems::class);
    }

    public function getCart($userId = null, $sessionId = null)
    {
        if ($userId) {
            $cart = CartData::firstOrCreate(['user_id' => $userId]);
        } else {
            $cart = CartData::firstOrCreate(['session_id' => $sessionId, 'user_id' => $userId]);
        }
        $cart->load('items');
        return $cart;
    }

    public function removeItem(CartData $cart, $product_id)
    {
        $cart->items()->where('product_id', $product_id)->delete();
    }

    public function deleteCart(CartData $cart)
    {
        $cart->items()->delete();
        $cart->delete();
    }

    public function swapCartWithAnother(CartData $cartWithSessionId, CartData $cartWithUserId, $userId)
    {
        if (!$cartWithSessionId->items()->exists()) {
            $cartWithSessionId->delete();
            return 0;
        }

        DB::transaction(function () use ($cartWithSessionId, $cartWithUserId, $userId) {
            $this->deleteCart($cartWithUserId);
            $cartWithSessionId->user_id = $userId;
            $cartWithSessionId->save();
        });
    }
}
