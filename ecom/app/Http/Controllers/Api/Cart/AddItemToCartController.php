<?php

namespace App\Http\Controllers\Api\Cart;

use App\Models\Product;
use App\Models\CartData;
use App\Models\CartItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class AddItemToCartController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, CartData $helper)
    {
        try {

            if (!(Product::query()->find($request->input('product_id'))->exists())) {
                report('Item does not exist');
                return response()->json(['success' => false]);
            }

            $product = Product::query()->find($request->input('product_id'));
            $quantity = $request->filled('qty') ? $request->qty : 1;

            $user = $request->user();
            $sessionId = $request->session()->getId();
            $cart = $helper->getCart($user, $sessionId);

            if ($quantity < 1) {
                $helper->removeItem($cart, $product->id);
                return response()->json(['success' => true]);
            }

            $addOrSet = $request->input('addOrSet');

            DB::transaction(function () use ($product, $addOrSet, $quantity, $cart) {
                $cartItem = $cart->items()->firstOrCreate(
                    [
                        'product_id' => $product->id
                    ],
                    [
                        'qty' => 0,
                    ]
                );

                $cartItem->product_data = $product;

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
            $cart->touch();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            report($th);
            return response()->json(['success' => false]);
        }
    }
}
