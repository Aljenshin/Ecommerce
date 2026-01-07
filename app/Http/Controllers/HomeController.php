<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->get();
        $featuredProducts = Product::where('is_active', true)
            ->with('category')
            ->latest()
            ->take(8)
            ->get();

        return view('home', compact('categories', 'featuredProducts'));
    }
}
