<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartData;
use App\Models\CartItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function getItems(Request $request, CartData $helper)
    {
        $userId = auth()->id();
        $sessionId = $request->session()->getId();

        $cart = $helper->getCart($userId, $sessionId);
        return response()->json(['cart' => $cart]);
    }

    public function removeItem(Request $request, CartData $helper)
    {
        $this->validate($request, [
            'cartitem_id' => 'required|numeric'
        ]);

        $userId = auth()->id();
        $sessionId = $request->session()->getId();
        $cart = $helper->getCart($userId, $sessionId);

        try {
            $cart->removeItem($request->cartitem_id);
        } catch (\Throwable $th) {
            report($th);
            return response()->json(['success' => false]);
        }
        $cart->load('items.product');
        return response()->json(['success' => true, 'cart' => $cart]);
    }
}
