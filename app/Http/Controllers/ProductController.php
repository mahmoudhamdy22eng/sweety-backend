<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::all();

        if ($products->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'No products found'], 404);
        }

        return response()->json($products, 200);
    }

    public function store(Request $request)
    {
        $product = Product::create($request->all());
        return response()->json($product, 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->update($request->all());
            return response()->json(['status' => true, 'message' => 'Product updated successfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'Product not found'], 404);
        }
    }

    public function show($id)
    {
        $product = Product::find($id);

        if ($product) {
            return response()->json($product, 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Product not found'], 404);
        }
    }

}
