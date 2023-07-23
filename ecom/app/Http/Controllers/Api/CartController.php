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
    public function getItems(Request $request, CartData $helper)
    {
        $cart = $helper->getCart($request);

        return $cart->with('items')->first();
    }

    public function removeItem(Request $request, CartData $helper)
    {
        try {
            $cart = $helper->getCart($request);
            Log::info($request->product_id);
            $helper->removeItem($cart, $request->product_id);
        } catch (\Throwable $th) {
            report($th);
            return response()->json(['success' => false]);
        }

        return response()->json(['success' => true]);
    }
}
