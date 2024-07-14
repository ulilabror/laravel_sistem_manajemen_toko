<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('files')->get();
        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::with('files')->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:255',
            'product_type' => 'required|string|max:255',
            'product_sku' => 'required|string|max:255|unique:products',
            'product_description' => 'required|string',
            'price' => 'required|integer',
            'files.*' => 'sometimes|file|mimes:jpeg,png,jpg,gif,svg|max:2048' // Adjust validation for multiple files
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation Failed', 'errors' => $validator->errors()], 422);
        }

        $product = Product::create([
            'product_name' => $request->product_name,
            'product_type' => $request->product_type,
            'product_sku' => $request->product_sku,
            'product_description' => $request->product_description,
            'price' => $request->price,
            'created_by' => Auth::user()->id
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/files', $filename);

                $product->files()->create([
                    'filename' => $filename,
                    'path' => $path,
                    'uploaded_by' => Auth::user()->id,
                    'related_id' => $product->id,
                    'related_type' => Product::class,
                ]);
            }
        }

        return response()->json(['message' => 'Product created successfully', 'product' => new ProductResource($product)], 200);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:255',
            'product_type' => 'required|string|max:255',
            'product_sku' => 'required|string|max:255|unique:products,product_sku,' . $id,
            'product_description' => 'required|string',
            'price' => 'required|integer',
            'file' => 'sometimes|file|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation Failed', 'errors' => $validator->errors()], 422);
        }

        $product->update([
            'product_name' => $request->product_name,
            'product_type' => $request->product_type,
            'product_sku' => $request->product_sku,
            'product_description' => $request->product_description,
            'price' => $request->price,
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/files', $filename);

            $product->files()->create([
                'filename' => $filename,
                'path' => $path,
                'uploaded_by' => Auth::id(),
                'related_id' => $product->id,
                'related_type' => Product::class,
            ]);
        }

        return response()->json(['message' => 'Product updated successfully', 'product' => $product]);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
