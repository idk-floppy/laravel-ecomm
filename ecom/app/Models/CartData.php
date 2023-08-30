<?php

namespace App\Models;

use App\Http\Resources\CartItemResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartData extends Model
{
    use HasFactory;
    public $timestamps = true;
    public $fillable = ['user_id', 'session_id'];
    protected $appends = ['total'];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
    {
        return $this->hasMany(CartItems::class);
    }

    // Methods
    public function getCart($userId = null, $sessionId = null)
    {
        $findOrCreateBy = $userId ? ['user_id' => $userId] : ['session_id' => $sessionId, 'user_id' => $userId];

        $cart = CartData::firstOrCreate($findOrCreateBy);

        // $cart->load(['user', 'items.product']);
        return $cart;
    }

    public function removeItem($cartitem_id)
    {
        $this->items()->where('id', $cartitem_id)->delete();
    }

    public function deleteCart()
    {
        $this->items()->delete();
        $this->delete();
    }

    public function swapCartWithAnother(CartData $cartWithSessionId, CartData $cartWithUserId, $userId)
    {
        if (!$cartWithSessionId->items()->exists()) {
            $cartWithSessionId->delete();
            return 0;
        }

        DB::transaction(function () use ($cartWithSessionId, $cartWithUserId, $userId) {
            $user = User::find($userId);

            $cartWithUserId->deleteCart();
            $cartWithSessionId->user()->associate($user);
            $cartWithSessionId->session_id = null;
            $cartWithSessionId->save();
        });
    }

    // Attributes
    public function getTotalAttribute()
    {
        $items = $this->items;
        $total = 0;

        foreach ($items as $item) {
            $total += $item->subtotal;
        }
        return $total;
    }
}
