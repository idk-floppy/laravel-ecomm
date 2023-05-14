<?php

namespace App\Http\Controllers;

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
            'index', 'show'
        ]]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search');
        $orderBy = request('orderBy');
        $minPrice = request('minPrice');
        $maxPrice = request('maxPrice');

        $products = Product::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%" . $search . "%");
            })
            ->when($minPrice, function ($query, $minPrice) {
                return $query->where('price', '>=', $minPrice);
            })
            ->when($maxPrice, function ($query, $maxPrice) {
                return $query->where('price', '<=', $maxPrice);
            })
            ->when($orderBy, function ($query, $orderBy) {
                return $query->orderBy($orderBy, 'ASC');
            })
            ->paginate(12);

        return ProductResource::collection($products);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $img = $request->file('image')->store('images', 'public');

        $data = $request->post();
        $product = DB::transaction(function () use ($data, $img) {
            $product = Product::create(['name' => $data['name'], 'price' => $data['price'], 'image' => $img]);
            return $product;
        });
        return Redirect::route('products.show', ['product' => $product]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('products.show', ['product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', ['product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->post();
        try {
            DB::transaction(function () use ($data, $product, $request) {
                if ($request->file('image')) {
                    if ($product->image) {
                        Storage::delete($product->image);
                    }
                    $img = $request->file('image')->store('images', 'public');
                    $product->image = $img;
                }
                $product->name = $data['name'];
                $product->price = $data['price'];
                $product->save();
            });
        } catch (\Throwable $th) {
            report($th);
            return response()->json(['success' => false]);
        }

        return Redirect::route('products.show', ['product' => $product]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            DB::transaction(function () use ($product) {
                if ($product->image) {
                    Storage::delete($product->image);
                }
                CartItems::where('product_id', $product->id)->delete();
                $product->delete();
            });
        } catch (\Throwable $th) {
            return response()->json(['success' => false]);
        }

        return response()->json(['success' => true]);
    }
}
