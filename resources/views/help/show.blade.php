@extends('layouts.app')

@section('title', $title . ' - Help Center - Uni-H-Pen')

@section('content')
<div class="mb-8">
    <!-- Breadcrumb -->
    <nav class="mb-6 text-sm">
        <ol class="flex items-center space-x-2 text-gray-600">
            <li><a href="{{ route('home') }}" class="hover:text-blue-600">Home</a></li>
            <li><span>/</span></li>
            <li><a href="{{ route('help.index') }}" class="hover:text-blue-600">Help Center</a></li>
            <li><span>/</span></li>
            <li class="text-gray-900 font-medium">{{ $title }}</li>
        </ol>
    </nav>

    <div class="bg-white rounded-lg shadow-md p-8">
        <h1 class="text-4xl font-bold mb-6">{{ $title }}</h1>

        @if($slug === 'faq')
            <div class="space-y-6">
                <div>
                    <h3 class="text-xl font-semibold mb-2">How do I place an order?</h3>
                    <p class="text-gray-700">Browse our products, add items to cart, and proceed to checkout. You can also use "Buy Now" for instant checkout.</p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-2">Can I modify or cancel my order?</h3>
                    <p class="text-gray-700">You can cancel your order within 24 hours of placing it. Once processed, orders cannot be modified.</p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-2">How do I track my order?</h3>
                    <p class="text-gray-700">Go to "My Orders" in your account to track your order status. You'll receive email notifications at each stage.</p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-2">What payment methods do you accept?</h3>
                    <p class="text-gray-700">We accept all major credit cards, debit cards, and digital payment methods.</p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-2">How long does shipping take?</h3>
                    <p class="text-gray-700">Standard shipping takes 3-5 business days. Express shipping (1-2 days) is available for an additional fee.</p>
                </div>
            </div>

        @elseif($slug === 'shipping')
            <div class="space-y-6">
                <div>
                    <h3 class="text-xl font-semibold mb-2">Shipping Options</h3>
                    <ul class="list-disc list-inside text-gray-700 space-y-1">
                        <li><strong>Standard Shipping:</strong> 3-5 business days - Free on orders over $50</li>
                        <li><strong>Express Shipping:</strong> 1-2 business days - $10</li>
                        <li><strong>Overnight:</strong> Next business day - $20</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-2">Delivery Times</h3>
                    <p class="text-gray-700">Orders are processed within 1-2 business days. You'll receive a tracking number once your order ships.</p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-2">International Shipping</h3>
                    <p class="text-gray-700">We ship worldwide. International orders typically take 7-14 business days. Additional fees may apply.</p>
                </div>
            </div>

        @elseif($slug === 'returns')
            <div class="space-y-6">
                <div>
                    <h3 class="text-xl font-semibold mb-2">30-Day Return Policy</h3>
                    <p class="text-gray-700">You can return items within 30 days of delivery. Items must be unworn, unwashed, and in original packaging with tags attached.</p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-2">How to Return</h3>
                    <ol class="list-decimal list-inside text-gray-700 space-y-1">
                        <li>Go to "My Orders" in your account</li>
                        <li>Select the item you want to return</li>
                        <li>Click "Return Item" and follow the instructions</li>
                        <li>Print the return label and ship the item back</li>
                    </ol>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-2">Refund Processing</h3>
                    <p class="text-gray-700">Refunds are processed within 5-7 business days after we receive your return. You'll receive an email confirmation once processed.</p>
                </div>
            </div>

        @elseif($slug === 'payments')
            <div class="space-y-6">
                <div>
                    <h3 class="text-xl font-semibold mb-2">Accepted Payment Methods</h3>
                    <ul class="list-disc list-inside text-gray-700 space-y-1">
                        <li>Credit Cards (Visa, Mastercard, American Express)</li>
                        <li>Debit Cards</li>
                        <li>PayPal</li>
                        <li>Digital Wallets</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-2">Secure Payments</h3>
                    <p class="text-gray-700">All transactions are encrypted and secure. We never store your full payment information.</p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-2">Payment Issues</h3>
                    <p class="text-gray-700">If you experience payment issues, please contact our support team. Most issues are resolved within 24 hours.</p>
                </div>
            </div>

        @elseif($slug === 'account')
            <div class="space-y-6">
                <div>
                    <h3 class="text-xl font-semibold mb-2">Managing Your Account</h3>
                    <p class="text-gray-700">Update your profile, shipping address, and payment information in your account settings.</p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-2">Order History</h3>
                    <p class="text-gray-700">View all your past and current orders in "My Orders". Track shipments and view order details.</p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-2">Changing Password</h3>
                    <p class="text-gray-700">Go to account settings to change your password. Make sure to use a strong, unique password.</p>
                </div>
            </div>

        @elseif($slug === 'contact')
            <div class="space-y-6">
                <div>
                    <h3 class="text-xl font-semibold mb-2">Contact Support</h3>
                    <p class="text-gray-700 mb-4">We're here to help! Get in touch with us through any of these channels:</p>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="space-y-4">
                            <div>
                                <h4 class="font-semibold mb-1">Email</h4>
                                <p class="text-gray-700">support@uni-h-pen.com</p>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1">Phone</h4>
                                <p class="text-gray-700">+1 (555) 123-4567</p>
                                <p class="text-sm text-gray-500">Monday - Friday, 9 AM - 6 PM EST</p>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1">Live Chat</h4>
                                <p class="text-gray-700">Available 24/7 on our website</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-8 pt-8 border-t">
            <p class="text-gray-600">Still need help? <a href="{{ route('help.show', 'contact') }}" class="text-blue-600 hover:text-blue-700 font-semibold">Contact our support team</a></p>
        </div>
    </div>
</div>
@endsection

