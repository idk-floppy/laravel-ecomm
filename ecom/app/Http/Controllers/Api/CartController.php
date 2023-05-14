<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\CartData;
use App\Models\CartItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class CartController extends Controller
{

    public function addItem(Request $request)
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
                $cartItem->product_data = Product::find($cartItem->product_id);
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

    public function getItems(Request $request)
    {
        $user = $request->user();
        $cartData = CartData::query()->where('session_id', $request->session()->getId());

        $cartData->when($user, function ($q) use ($user) {
            return $q->orWhere('user_id', $user->id);
        });

        return $cartData->with('items')->first();
    }
}