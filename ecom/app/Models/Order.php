<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    public function items()
    {
        return $this->hasMany(OrderItems::class, 'order_id', 'id');
    }

    public function summary()
    {
        $summary = collect($this->items)->sum('subtotal');
        print($summary);
    }
}
