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
        $user = $request->user();
        $sessionId = $request->session()->getId();
        $cart = $helper->getCart($user, $sessionId);
        return $cart;
    }

    public function removeItem(Request $request, CartData $helper)
    {
        try {
            $cart = $helper->getCart($request);
            $helper->removeItem($cart, $request->product_id);
        } catch (\Throwable $th) {
            report($th);
            return response()->json(['success' => false]);
        }

        return response()->json(['success' => true]);
    }
}
