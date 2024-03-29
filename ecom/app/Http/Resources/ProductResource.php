<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->imageURL,
            // 'slug' => $this->slug,
            // 'description' => substr($this->description, 0, 40) . "...",
            'price' => $this->price,
            'price_display' => number_format($this->price, 0, '.', ' ') . " Ft",
        ];
    }
}