<?php

namespace App\Http\Controllers\Api;

use App\Models\CartData;
use App\Models\CartItems;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function addItem(Request $request)
    {
        try {
            // $session_id = $request->session()->getId();
            Log::alert($request->session()->getId());
            // $cart_data = CartData::firstOrCreate(['session_id' => $session_id]);
            // $cart_item = new CartItems([
            //     'product_id' => $request->input('product_id'),
            //     'product_data' => $request->input('product_data'),
            // ]);
            // $cart_data->items()->save($cart_item);
            // return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            report($th);
            // return response()->json(['success' => false]);
        }
    }
}
