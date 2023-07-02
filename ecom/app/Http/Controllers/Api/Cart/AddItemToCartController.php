<?php

namespace App\Http\Controllers\Api\Cart;

use App\Models\Product;
use App\Models\CartData;
use App\Models\CartItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AddItemToCartController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $user = $request->user();
            $addOrSet = $request->input('addOrSet');
            $sessionId = $request->session()->getId();
            $quantity = $request->filled('qty') ? $request->qty : 1;

            if ($user) {
                $cartData = CartData::firstOrCreate(['user_id' => $user->id]);
            } else {
                $cartData = CartData::firstOrCreate(['session_id' => $sessionId]);
            }

            if ($quantity < 1) {
                CartItems::where([
                    'cart_data_id' => $cartData->id,
                    'product_id' => $request->input('product_data')
                ])->delete();
                return response()->json(['success' => true]);
            }

            DB::transaction(function () use ($request, $addOrSet, $sessionId, $quantity, $cartData) {
                $cartItem = $cartData->items()->firstOrCreate(
                    [
                        'product_id' => $request->input('product_data')
                    ],
                    [
                        'qty' => 0,
                    ]
                );
                $cartItem->product_data = Product::find($cartItem->product_id);
                switch ($addOrSet) {
                    case 'set':
                        $cartItem->qty = $quantity;
                        break;

                    case 'add':
                        $cartItem->qty += $quantity;
                        break;
                }

                $cartItem->save();
            });
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            report($th);
            return response()->json(['success' => false]);
        }
    }
}