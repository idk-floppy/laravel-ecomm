<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    public $fillable = ['name', 'price', 'image'];

    // Attributes
    public function getImageURLAttribute()
    {
        return url('storage/', $this->image);
    }

    // Scopes
    public function scopeFilterByName($query, $name)
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }

    public function scopeFilterByMinPrice($query, $minPrice)
    {
        return $query->where('price', '>=', $minPrice);
    }

    public function scopeFilterByMaxPrice($query, $maxPrice)
    {
        return $query->where('price', '<=', $maxPrice);
    }


    // Methods
    public function saveImage($image)
    {
        if ($this->image) {
            Storage::delete($this->image);
        }
        $imgUrl = $image->store('images', 'public');
        $this->image = $imgUrl;
    }
}
