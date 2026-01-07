<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cartItems = CartItem::where('user_id', Auth::id())
            ->with('product')
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:' . $product->stock],
            'size' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:50'],
        ]);

        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->where('size', $request->size)
            ->where('color', $request->color)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'size' => $request->size,
                'color' => $request->color,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:' . $cartItem->product->stock],
        ]);

        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return redirect()->route('cart.index')->with('success', 'Cart updated!');
    }

    public function remove(CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
    }
}
