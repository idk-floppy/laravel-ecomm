<?php

namespace App\Http\Controllers\Cart;

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
            if (!(Product::query()->find($request->input('product_id'))->exists())) {
                return response()->json(['success' => false, 'message' => 'Product does not exist']);
            }

            $product = Product::query()->find($request->input('product_id'));
            $quantity = $request->filled('qty') ? $request->qty : 1;

            $userId = Auth::check() ? Auth::user()->id : null;
            $sessionId = $request->session()->getId();

            // Just so we can "add" more by clicking "add to cart", or "set" it to a certain value by overwriting it in the cart.
            $addOrSet = $request->input('addOrSet');
            $cart = $helper->getCart($userId, $sessionId);

            if ($quantity < 1) {
                $helper->removeItem($cart, $product->id);
                $cart->load('items.product');
                return response()->json(['success' => true, 'cart' => $cart]);
            }

            $cartItem = $cart->items()->firstOrCreate(
                [
                    'product_id' => $product->id
                ],
                [
                    'qty' => 0,
                ]
            );

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

            $cart = $helper->getCart($userId, $sessionId);
            return response()->json(['success' => true, 'cart' => $cart]);
        } catch (\Throwable $th) {
            report($th);
            return response()->json(['success' => false]);
        }
    }
}
