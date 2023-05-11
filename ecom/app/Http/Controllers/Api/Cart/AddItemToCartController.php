<?php

namespace App\Http\Controllers\Api\Cart;

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
            $method = $request->input('method');
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
                    'product_id' => json_decode($request->input('product_data'))->id,
                ])->delete();
                return response()->json(['success' => true]);
            }

            DB::transaction(function () use ($request, $method, $sessionId, $quantity, $cartData) {
                $cartItem = $cartData->items()->firstOrCreate(
                    [
                        'product_id' => json_decode($request->input('product_data'))->id
                    ],
                    [
                        'qty' => 0,
                        'product_data' => $request->input('product_data')
                    ]
                );
                switch ($method) {
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