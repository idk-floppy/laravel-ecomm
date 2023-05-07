<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CartData extends Model
{
    use HasFactory;
    public $timestamps = false;

    public $fillable = ['user_id', 'session_id'];

    public function items()
    {
        return $this->hasMany(CartItems::class);
    }
}
