<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductCategory;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::all();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $category = ProductCategory::create($request->all());
        return response()->json($category, 201);
    }

    public function show($id)
    {
        $category = ProductCategory::findOrFail($id);
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $category = ProductCategory::findOrFail($id);
        $category->update($request->all());
        return response()->json($category);
    }

    public function destroy($id)
    {
        $category = ProductCategory::findOrFail($id);
        $category->delete();
        return response()->json(null, 204);
    }
}

