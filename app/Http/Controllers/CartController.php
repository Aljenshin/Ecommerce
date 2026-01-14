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
            ->with(['product.category', 'product.brand', 'product.images'])
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function count()
    {
        $count = CartItem::where('user_id', Auth::id())->count();
        return response()->json(['count' => $count]);
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:' . $product->stock],
            'size' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:50'],
        ]);

        $size = $request->size ?: null;
        $color = $request->color ?: null;

        // Normalize empty strings to null
        $size = ($size === '') ? null : $size;
        $color = ($color === '') ? null : $color;

        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->where(function($query) use ($size) {
                if ($size === null) {
                    $query->whereNull('size');
                } else {
                    $query->where('size', $size);
                }
            })
            ->where(function($query) use ($color) {
                if ($color === null) {
                    $query->whereNull('color');
                } else {
                    $query->where('color', $color);
                }
            })
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'size' => $size,
                'color' => $color,
            ]);
        }

        return redirect()->route('cart.index')
            ->with('success', 'Product added to cart!')
            ->with('cart_updated', true);
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

        return redirect()->route('cart.index')
            ->with('success', 'Cart updated!')
            ->with('cart_updated', true);
    }

    public function remove(CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        $cartItem->delete();

        return redirect()->route('cart.index')
            ->with('success', 'Item removed from cart!')
            ->with('cart_updated', true);
    }
}
