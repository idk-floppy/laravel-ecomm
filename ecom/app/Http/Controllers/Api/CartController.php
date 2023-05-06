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
            $sessionId = $request->session()->getId();
            $cartData = CartData::firstOrCreate(['session_id' => $sessionId]);
            $cartItem = $cartData->items()->firstOrCreate(
                [
                    'product_id' => json_decode($request->input('product_data'))->id
                ],
                [
                    'qty' => 0,
                    'product_data' => $request->input('product_data')
                ]
            );
            $cartItem->qty++;
            $cartItem->save();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            report($th);
            return response()->json(['success' => false]);
        }
    }
}
