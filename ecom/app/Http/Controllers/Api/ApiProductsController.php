<?php

namespace App\Http\Controllers\api;

use App\Models\Product;
use App\Models\CartItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProductSubmitRequest;

class ApiProductsController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth', ['except' => [
    //         'index', 'show'
    //     ]]);
    // }
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
     * Store a newly created resource in storage.
     */
    public function store(ProductSubmitRequest $request)
    {
        try {
            $img = $request->file('image')->store('images', 'public');
            $data = $request->post();
            $product = DB::transaction(function () use ($data, $img) {
                return Product::create(['name' => $data['name'], 'price' => $data['price'], 'image' => $img]);;
            });
            return response()->json(['success' => true, 'product' => route('products.show', $product->id)]);
        } catch (\Throwable $th) {
            report($th);
            return response()->json(['success' => false]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductSubmitRequest $request, Product $product)
    {
        $data = $request->post();
        try {
            DB::transaction(function () use ($data, $product, $request) {
                if ($request->file('image')) {
                    if ($product->image) {
                        Storage::delete($product->image);
                        Log::info('Image ' . $product->image . ' deleted');
                    }
                    $img = $request->file('image')->store('images', 'public');
                    $product->image = $img;
                }
                $product->name = $data['name'];
                $product->price = $data['price'];
                $product->save();
            });
            return Redirect::route('products.show', ['product' => $product]);
        } catch (\Throwable $th) {
            report($th);
            return response()->json(['success' => false]);
        }
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
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            report($th);
            return response()->json(['success' => false]);
        }
    }
}
