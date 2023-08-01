<?php

namespace App\Http\Controllers\Api;

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
        $userId = Auth::check() ? Auth::user()->id : null;
        $sessionId = $request->session()->getId();

        $cart = $helper->getCart($userId, $sessionId);
        return $cart;
    }

    public function removeItem(Request $request, CartData $helper)
    {
        $this->validate($request, [
            'product_id' => 'required|numeric'
        ]);

        $userId = Auth::check() ? Auth::user()->id : null;
        $sessionId = $request->session()->getId();

        try {
            $cart = $helper->getCart($userId, $sessionId);

            $cart->removeItem($request->product_id);
        } catch (\Throwable $th) {
            report($th);
            return response()->json(['success' => false]);
        }

        return response()->json(['success' => true]);
    }
}
