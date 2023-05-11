<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Redirect;

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
        $products = Product::query()->when($search, function ($query, $search) {
            return $query->where('name', 'like', "%" . $search . "%");
        })->paginate(12);

        return ProductResource::collection($products);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->post();
        Log::info($data);
        DB::transaction(function () use ($data) {
            Product::create(['name' => $data['name'], 'price' => $data['price']]);
        });
        // return Redirect::route('products.show');
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
        $data = $request->all();
        try {
            DB::transaction(function () use ($data, $product) {
                $product->fill($data);
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
        DB::transaction(function () use ($product) {
            $product->delete();
        });
        return response()->noContent();
    }
}
