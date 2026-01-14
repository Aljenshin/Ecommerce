<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'brand', 'images'])->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::where('is_active', true)->get();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'images' => ['nullable', 'array', 'min:1', 'max:5'],
            'images.*' => ['image', 'max:2048'],
            'is_active' => ['boolean'],
        ]);

        $data = [
            'name' => $request->name,
            'slug' => Product::generateUniqueSlug($request->name),
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'is_active' => $request->has('is_active'),
        ];

        // Keep single image for backward compatibility
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);

        // Handle multiple images
        if ($request->hasFile('images')) {
            $sortOrder = 0;
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'sort_order' => $sortOrder++,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::where('is_active', true)->get();
        $product->load('images');
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'images' => ['nullable', 'array', 'min:1', 'max:5'],
            'images.*' => ['image', 'max:2048'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['exists:product_images,id'],
            'is_active' => ['boolean'],
        ]);

        $data = [
            'name' => $request->name,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'is_active' => $request->has('is_active'),
        ];

        // Only update slug if name changed
        if ($request->name !== $product->name) {
            $data['slug'] = Product::generateUniqueSlug($request->name, $product->id);
        }

        // Keep single image for backward compatibility
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Handle deletion of product images
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $productImage = ProductImage::find($imageId);
                if ($productImage && $productImage->product_id === $product->id) {
                    Storage::disk('public')->delete($productImage->image_path);
                    $productImage->delete();
                }
            }
        }

        $product->update($data);

        // Handle new multiple images
        if ($request->hasFile('images')) {
            $existingImagesCount = $product->images()->count();
            $maxImages = 5;
            $remainingSlots = $maxImages - $existingImagesCount;
            
            if ($remainingSlots > 0) {
                $sortOrder = $product->images()->max('sort_order') ?? -1;
                $uploadedCount = 0;
                
                foreach ($request->file('images') as $image) {
                    if ($uploadedCount >= $remainingSlots) {
                        break;
                    }
                    $imagePath = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                        'sort_order' => ++$sortOrder,
                    ]);
                    $uploadedCount++;
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Delete all product images
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
