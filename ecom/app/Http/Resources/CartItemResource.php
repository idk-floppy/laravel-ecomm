<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cart_data_id' => $this->cart_data_id,
            'product' => $this->product,
            'product_id' => $this->product_id,
            'qty' => $this->qty,
            'subtotal' => "{$this->subtotal} Ft"
        ];
    }
}
