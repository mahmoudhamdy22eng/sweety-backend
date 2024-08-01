<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Log; // Add this line


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')->get();

        if ($products->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'No products found'], 404);
        }

        $products = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'QuantityAvailable' => $product->QuantityAvailable,
                'CategoryID' => $product->CategoryID,
                'CategoryName' => $product->category->CategoryName,
                'AdminID' => $product->AdminID,
                'IsCustomizable' => $product->IsCustomizable,
                'HasNutritionalInfo' => $product->HasNutritionalInfo,
                'image' => $product->image,
                'vendor' => $product->vendor,
                'is_deleted' => $product->is_deleted
            ];
        });

        return response()->json($products, 200);
    }


    public function show($id)
    {
        $product = Product::with('category')->find($id);

        if ($product) {
            return response()->json([
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'QuantityAvailable' => $product->QuantityAvailable,
                'CategoryID' => $product->CategoryID,
                'CategoryName' => $product->category->CategoryName,
                'AdminID' => $product->AdminID,
                'IsCustomizable' => $product->IsCustomizable,
                'HasNutritionalInfo' => $product->HasNutritionalInfo,
                'image' => $product->image,
                'vendor' => $product->vendor,
                'is_deleted' => $product->is_deleted
            ], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Product not found'], 404);
        }
    }



    public function store(Request $request)
{
     // Log the incoming request data
    Log::info('Incoming request data:', $request->all());
    
    // Validate incoming request
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'QuantityAvailable' => 'required|integer',
        'CategoryID' => 'required|integer',
        'AdminID' => 'required|integer',
        'IsCustomizable' => 'required|boolean',
        'HasNutritionalInfo' => 'required|boolean',
        'vendor' => 'required|string|max:255',
        'image' => 'nullable|file|image|max:2048' // Validate image file
    ]);

    $product = new Product();
    $product->name = $request->name;
    $product->description = $request->description;
    $product->price = $request->price;
    $product->QuantityAvailable = $request->QuantityAvailable;
    $product->CategoryID = $request->CategoryID;
    $product->AdminID = $request->AdminID;
    $product->IsCustomizable = $request->IsCustomizable;
    $product->HasNutritionalInfo = $request->HasNutritionalInfo;
    $product->vendor = $request->vendor;
    $product->is_deleted = false;

    if ($request->hasFile('image')) {
        // Store the uploaded image in the 'public/images/dashboard/products/sweets' directory
        $path = $request->file('image')->store('dashboard/products/sweets', 'public');
        $product->image = $path;
    }

    $product->save();

    return response()->json($product, 201);
}


public function update(Request $request, $id)
{
    // Log the incoming request data
    // Log::info('Incoming request data:', $request->all()); 
    // var_dump($request->all());
    
    $product = Product::find($id);
    if (!$product) {
        return response()->json(['status' => false, 'message' => 'Product not found'], 404);
    }
    
    // echo $product;
    // echo $request->name;
    // echo $request->description;
    // echo $request->price;
    // echo $request->QuantityAvailable;
    // echo $request->CategoryID;
    // echo $request->AdminID;
    // echo $request->IsCustomizable;
    // echo $request->HasNutritionalInfo;
    // echo $request->vendor;
    // echo "\n test";
    try {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'QuantityAvailable' => 'required|integer',
            'CategoryID' => 'required|integer',
            'AdminID' => 'required|integer',
            'IsCustomizable' => 'required|boolean',
            'HasNutritionalInfo' => 'required|boolean',
            'vendor' => 'required|string|max:255',
            'image' => 'nullable|file|image|max:2048' // Validate image file
        ]);

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->QuantityAvailable = $request->QuantityAvailable;
        $product->CategoryID = $request->CategoryID;
        $product->AdminID = $request->AdminID;
        $product->IsCustomizable = $request->IsCustomizable;
        $product->HasNutritionalInfo = $request->HasNutritionalInfo;
        $product->vendor = $request->vendor;

        if ($request->hasFile('image')) {
            // Delete the old image if exists
            if ($product->image) {
                \Storage::delete('public/' . $product->image);
            }

            // Store the uploaded image in the 'public/images/dashboard/products/sweets' directory
            $path = $request->file('image')->store('dashboard/products/sweets', 'public');
            $product->image = $path;
        }

        $product->save();

        return response()->json(['status' => true, 'message' => 'Product updated successfully']);
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation Errors: ', $e->errors());
        return response()->json(['status' => false, 'errors' => $e->errors()], 422);
    }
}



}
