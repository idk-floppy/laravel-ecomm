<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductSubmitRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Intervention\Image\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\ProductResource;
use App\Models\CartItems;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => [
            'show'
        ]]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Display the specified resource.
     */
    public function show($product_id)
    {
        return view('products.show', ['product_id' => $product_id]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($product_id)
    {
        return view('products.edit', ['product_id' => $product_id]);
    }
}