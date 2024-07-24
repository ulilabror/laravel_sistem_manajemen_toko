<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Resources\BaseResource;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::with('files')->get();
            return new BaseResource(true, $products, 'Products retrieved successfully', null);
        } catch (\Exception $e) {
            return new BaseResource(false, null, 'Failed to retrieve products', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $product = Product::with('files')->find($id);

            if (!$product) {
                return new BaseResource(false, null, 'Product not found', null);
            }

            return new BaseResource(true, new ProductResource($product), 'Product retrieved successfully', null);
        } catch (\Exception $e) {
            return new BaseResource(false, null, 'Failed to retrieve product', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->only([
            'product_name', 'product_type', 'product_label', 'product_barcode_id',
            'product_sku', 'product_description', 'price', 'files'
        ]), [
            'product_name' => 'required|string|max:255',
            'product_type' => 'required|string|max:255',
            'product_label' => 'sometimes|max:20',
            'product_barcode_id' => 'sometimes|max:255',
            'product_sku' => 'required|string|max:255|unique:products,product_sku',
            'product_description' => 'required|string',
            'price' => 'required|integer',
            'files.*' => 'sometimes|file|mimes:jpeg,png,jpg,gif,svg|max:2048' // Adjust validation for multiple files
        ]);

        if ($validator->fails()) {
            return new BaseResource(false, null, 'Validation Failed', $validator->errors());
        }

        try {
            $product = Product::create([
                'product_name' => $request->product_name,
                'product_type' => $request->product_type,
                'product_sku' => $request->product_sku,
                'product_label' => $request->product_label,
                'product_barcode_id' => $request->product_barcode_id,
                'product_description' => $request->product_description,
                'price' => $request->price,
                'created_by' => Auth::user()->id
            ]);

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $fileNameOriginal = $file->getClientOriginalName();
                    $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                    if (config('filesystems.disks.cdn.url')) {
                        // Store file on CDN
                        $path = $file->storeAs('files', $filename, 'cdn');
                        /** @var \Illuminate\Filesystem\FilesystemManager $disk */
                        $disk = Storage::disk('cdn');
                        $fullUrl = $disk->url($path);
                    } else {
                        // Store file locally
                        $path = $file->storeAs('public/files', $filename);
                        $fullUrl = url(Storage::url($path));
                    }
                    $product->files()->create([
                        'filename' => $fileNameOriginal,
                        'path' => $path,
                        'url' => $fullUrl,
                        'uploaded_by' => Auth::user()->id,
                        'related_id' => $product->id,
                        'related_type' => Product::class,
                    ]);
                }
            }

            return new BaseResource(true, new ProductResource($product->load('files')), 'Product created successfully', null);
        } catch (\Exception $e) {
            return new BaseResource(false, null, 'Failed to create product', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return new BaseResource(false, null, 'Product not found', null);
        }

        $validator = Validator::make($request->only([
            'product_name', 'product_type', 'product_label', 'product_barcode_id',
            'product_sku', 'product_description', 'price', 'files'
        ]), [
            'product_name' => 'required|string|max:255',
            'product_type' => 'required|string|max:255',
            'product_label' => 'sometimes|max:20',
            'product_barcode_id' => 'sometimes|max:255',
            'product_sku' => 'required|string|max:255|unique:products,product_sku',
            'product_description' => 'required|string',
            'price' => 'required|integer',
            'files.*' => 'sometimes|file|mimes:jpeg,png,jpg,gif,svg|max:2048' // Adjust validation for multiple files
        ]);

        if ($validator->fails()) {
            return new BaseResource(false, null, 'Validation Failed', $validator->errors());
        }

        try {
            $product->update([
                'product_name' => $request->product_name,
                'product_type' => $request->product_type,
                'product_sku' => $request->product_sku,
                'product_label' => $request->product_label,
                'product_barcode_id' => $request->product_barcode_id,
                'product_description' => $request->product_description,
                'price' => $request->price,
                'created_by' => Auth::user()->id
            ]);

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $fileNameOriginal = $file->getClientOriginalName();
                    $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                    if (config('filesystems.disks.cdn.url')) {
                        // Store file on CDN
                        $path = $file->storeAs('files', $filename, 'cdn');
                        /** @var \Illuminate\Filesystem\FilesystemManager $disk */
                        $disk = Storage::disk('cdn');
                        $fullUrl = $disk->url($path);
                    } else {
                        // Store file locally
                        $path = $file->storeAs('public/files', $filename);
                        $fullUrl = url(Storage::url($path));
                    }
                    $product->files()->create([
                        'filename' => $fileNameOriginal,
                        'path' => $path,
                        'url' => $fullUrl,
                        'uploaded_by' => Auth::user()->id,
                        'related_id' => $product->id,
                        'related_type' => Product::class,
                    ]);
                }
            }

            return new BaseResource(true, new ProductResource($product->load('files')), 'Product updated successfully', null);
        } catch (\Exception $e) {
            return new BaseResource(false, null, 'Failed to update product', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return new BaseResource(false, null, 'Product not found', null);
            }

            $product->delete();

            return new BaseResource(true, null, 'Product deleted successfully', null);
        } catch (\Exception $e) {
            return new BaseResource(false, null, 'Failed to delete product', $e->getMessage());
        }
    }
}
