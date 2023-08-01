<?php

namespace App\Http\Controllers\Api\Cart;

use App\Models\Product;
use App\Models\CartData;
use App\Models\CartItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AddItemToCartController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, CartData $helper)
    {
        try {

            // Check if the product exists. If it doesn't, just report it and throw a false success.
            // Just for safety tho.
            if (!(Product::query()->find($request->input('product_id'))->exists())) {
                report('Item does not exist');
                return response()->json(['success' => false]);
            }

            $product = Product::query()->find($request->input('product_id'));
            $quantity = $request->filled('qty') ? $request->qty : 1;

            // Get the userID and the session ID. We need to set it to either the user's ID or null, so when we query the cart, it can jump straight to finding it by sessionID.
            $userId = Auth::check() ? Auth::user()->id : null;
            $sessionId = $request->session()->getId();

            // Just so we can "add" more by clicking "add to cart", or "set" it to a certain value by overwriting it in the cart.
            $addOrSet = $request->input('addOrSet');

            DB::transaction(function () use ($product, $addOrSet, $quantity, $helper, $userId, $sessionId) {
                $cart = $helper->getCart($userId, $sessionId);

                if ($quantity < 1) {
                    $helper->removeItem($cart, $product->id);
                    return response()->json(['success' => true]);
                }

                $cartItem = $cart->items()->firstOrCreate(
                    [
                        'product_id' => $product->id
                    ],
                    [
                        'qty' => 0,
                    ]
                );

                // We store the product data in json, so we don't do unnecessary queries for the product name, price, etc.
                // Tho it might not be the best thing to do, it only refreshes when you modify the quantity in the cart.
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
                $cart->touch();
            });
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            report($th);
            return response()->json(['success' => false]);
        }
    }
}
