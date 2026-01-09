<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function checkout()
    {
        $cartItems = collect();
        $isBuyNow = false;
        
        // Check if this is a Buy Now checkout
        if (session()->has('buy_now')) {
            $buyNowData = session('buy_now');
            $product = Product::findOrFail($buyNowData['product_id']);
            
            // Load product with relationships
            $product->load(['category', 'brand']);
            
            // Create a temporary cart item object
            $tempItem = (object) [
                'id' => 'temp_' . time(),
                'product_id' => $product->id,
                'product' => $product,
                'quantity' => $buyNowData['quantity'],
                'size' => $buyNowData['size'] ?? null,
                'color' => $buyNowData['color'] ?? null,
                'is_buy_now' => true,
            ];
            
            $cartItems = collect([$tempItem]);
            $isBuyNow = true;
            // Don't clear session here - clear it in store after order is placed
        } else {
            // Regular cart checkout
            $cartItems = CartItem::where('user_id', Auth::id())
                ->with(['product.category', 'product.brand'])
                ->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
            }
        }

        $total = $cartItems->sum(function ($item) {
            $product = is_object($item->product) ? $item->product : Product::find($item->product_id);
            return $item->quantity * $product->price;
        });

        // Pre-fill with user's saved address from profile
        $user = Auth::user();

        return view('orders.checkout', compact('cartItems', 'total', 'user', 'isBuyNow'));
    }

    public function buyNow(Request $request)
    {
        $request->validate([
            'product' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'size' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:50'],
        ]);

        $product = Product::findOrFail($request->product);
        
        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Insufficient stock!');
        }

        // Temporarily add to cart for buy now (or use session)
        session(['buy_now' => [
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'size' => $request->size,
            'color' => $request->color,
        ]]);

        return redirect()->route('checkout');
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => ['required', 'string', 'max:255'],
            'shipping_city' => ['required', 'string', 'max:255'],
            'shipping_state' => ['nullable', 'string', 'max:255'],
            'shipping_postal_code' => ['required', 'string', 'max:20'],
            'shipping_country' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'buy_now_items' => ['nullable', 'array'],
        ]);

        // Handle Buy Now items from session or hidden fields
        $cartItems = collect();
        $isBuyNow = session()->has('buy_now') || $request->has('buy_now_items');
        
        if ($isBuyNow) {
            // Process buy now items from session or request
            if (session()->has('buy_now')) {
                $buyNowData = session('buy_now');
                $product = Product::findOrFail($buyNowData['product_id']);
                $tempItem = (object) [
                    'product_id' => $product->id,
                    'product' => $product,
                    'quantity' => $buyNowData['quantity'],
                    'size' => $buyNowData['size'] ?? null,
                    'color' => $buyNowData['color'] ?? null,
                ];
                $cartItems->push($tempItem);
            } elseif ($request->buy_now_items) {
                // Process buy now items from request
                foreach ($request->buy_now_items as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    $tempItem = (object) [
                        'product_id' => $product->id,
                        'product' => $product,
                        'quantity' => $item['quantity'],
                        'size' => $item['size'] ?? null,
                        'color' => $item['color'] ?? null,
                    ];
                    $cartItems->push($tempItem);
                }
            }
        } else {
            // Regular cart items
            $cartItems = CartItem::where('user_id', Auth::id())
                ->with('product')
                ->get();
        }

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        DB::beginTransaction();
        try {
            $user = Auth::user();
            
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'status' => 'pending',
                'shipping_address' => $request->shipping_address ?? $user->address,
                'shipping_city' => $request->shipping_city ?? $user->city,
                'shipping_state' => $request->shipping_state ?? $user->state,
                'shipping_postal_code' => $request->shipping_postal_code ?? $user->postal_code,
                'shipping_country' => $request->shipping_country ?? $user->country,
                'phone' => $request->phone ?? $user->phone,
            ]);

            foreach ($cartItems as $cartItem) {
                $product = is_object($cartItem->product) ? $cartItem->product : Product::find($cartItem->product_id ?? $cartItem->product->id ?? null);
                
                if (!$product) {
                    continue; // Skip if product not found
                }
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $cartItem->quantity,
                    'price' => $product->price,
                    'size' => $cartItem->size ?? null,
                    'color' => $cartItem->color ?? null,
                ]);

                $product->decrement('stock', $cartItem->quantity);
            }

            // Only delete cart items if not buy now
            if (!$isBuyNow) {
                CartItem::where('user_id', Auth::id())->delete();
            } else {
                // Clear buy now session after order is placed
                session()->forget('buy_now');
            }

            DB::commit();

            // Refresh order to get order_number
            $order->refresh();

            // Create notification for order placement
            Notification::create([
                'user_id' => Auth::id(),
                'type' => 'order_status',
                'title' => 'Order Placed Successfully!',
                'message' => "Your order #{$order->order_number} has been placed and is being processed.",
                'link' => route('orders.show', $order->id),
                'is_read' => false,
            ]);

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to place order. Please try again.');
        }
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $order->load('orderItems.product');

        return view('orders.show', compact('order'));
    }

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }
}
