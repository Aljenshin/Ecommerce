<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)->with(['category', 'brand', 'images']);

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Brand filter (supports multiple brands)
        if ($request->filled('brand')) {
            $brands = is_array($request->brand) ? $request->brand : [$request->brand];
            $query->whereIn('brand_id', $brands);
        }

        // Search filter
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        switch ($request->get('sort')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(24)->withQueryString();
        $categories = Category::withCount('products')->get();
        $brands = Brand::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories', 'brands'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'brand', 'ratings.user', 'images'])
            ->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with('brand')
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
