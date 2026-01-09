<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function toggle(Product $product)
    {
        $favorite = Favorite::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $message = 'Product removed from favorites';
        } else {
            Favorite::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
            ]);
            $message = 'Product added to favorites';
        }

        return back()->with('success', $message);
    }
}
