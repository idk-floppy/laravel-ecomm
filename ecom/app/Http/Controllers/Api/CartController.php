<?php

namespace App\Http\Controllers\Api;

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
            Log::info($user);
            $sessionId = $request->session()->getId();

            DB::transaction(function () use ($request, $sessionId, $user) {
                if ($user) {
                    $cartData = CartData::firstOrCreate(['user_id' => $user->id]);
                } else {
                    $cartData = CartData::firstOrCreate(['session_id' => $sessionId]);
                }

                $cartItem = $cartData->items()->firstOrCreate(
                    [
                        'product_id' => json_decode($request->input('product_data'))->id
                    ],
                    [
                        'qty' => 0,
                        'product_data' => $request->input('product_data')
                    ]
                );
                if ($request->input('qty') !== null) {
                    $cartItem->qty = $request->input('qty');
                } else {
                    $cartItem->qty++;
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
