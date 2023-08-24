<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\CartItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProductSubmitRequest;
use App\Http\Requests\ProductUpdateRequest;
use Illuminate\Support\Facades\Log;

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
        $query = Product::query();

        $filters = Product::$filters;

        foreach ($filters as $parameter => $method) {
            if (request()->has($parameter) && request()->input($parameter) != null) {
                $query->$method(request()->input($parameter));
            }
        }

        $products = $query->paginate(12);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductSubmitRequest $request)
    {
        try {
            $data = $request->validated();

            $product = DB::transaction(function () use ($data) {
                return Product::create(['name' => $data['name'], 'price' => $data['price'], 'image' => null]);
            });
            $product->saveImage($request->file('image'));
            $product->save();

            return response()->json(['success' => true, 'data' => $product]);
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
        return response()->json(['product' => new ProductResource($product)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $data = $request->validated();

        try {
            DB::transaction(function () use ($data, $product, $request) {
                if ($request->file('image')) {
                    $product->saveImage($request->file('image'));
                }
                $product->name = $data['name'];
                $product->price = $data['price'];
                $product->save();
            });
            return response()->json(['success' => true, 'data' => $product]);
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
