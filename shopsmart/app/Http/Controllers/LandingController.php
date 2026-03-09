<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LandingController extends Controller
{
    public function index()
    {
        try {
            // Get featured products
            $featuredProducts = Product::with(['category'])
                ->where('is_active', true)
                ->where('stock_quantity', '>', 0)
                ->orderBy('created_at', 'desc')
                ->limit(12)
                ->get();

            // Get categories with product counts
            $categories = Category::withCount(['products' => function($query) {
                $query->where('is_active', true);
            }])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

            // Get hot deals (products with discounts)
            $hotDeals = Product::with(['category'])
                ->where('is_active', true)
                ->where('discount_percentage', '>', 0)
                ->where('stock_quantity', '>', 0)
                ->orderBy('discount_percentage', 'desc')
                ->limit(6)
                ->get();

            // Get testimonials (you can create a testimonials table or use static data)
            $testimonials = $this->getTestimonials();

            // Get statistics
            $stats = [
                'totalCustomers' => $this->getTotalCustomers(),
                'totalProducts' => Product::where('is_active', true)->count(),
                'totalSales' => $this->getTotalSales(),
                'supportHours' => '24/7'
            ];

            return view('landing', compact(
                'featuredProducts',
                'categories',
                'hotDeals',
                'testimonials',
                'stats'
            ));
        } catch (\Exception $e) {
            \Log::error('Landing page error: ' . $e->getMessage());
            // Return view with default data if there's an error
            return view('landing', [
                'featuredProducts' => collect(),
                'categories' => collect(),
                'hotDeals' => collect(),
                'testimonials' => $this->getTestimonials(),
                'stats' => [
                    'totalCustomers' => 0,
                    'totalProducts' => 0,
                    'totalSales' => 0,
                    'supportHours' => '24/7'
                ]
            ]);
        }
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $products = Product::with(['category'])
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('sku', 'LIKE', "%{$query}%");
            })
            ->orderBy('name')
            ->paginate(12);

        return response()->json([
            'products' => $products,
            'total' => $products->total()
        ]);
    }

    public function getCategoryProducts($categoryId)
    {
        $products = Product::with(['category'])
            ->where('is_active', true)
            ->where('category_id', $categoryId)
            ->where('stock_quantity', '>', 0)
            ->orderBy('name')
            ->paginate(12);

        return response()->json([
            'products' => $products,
            'total' => $products->total()
        ]);
    }

    public function getProductDetails($productId)
    {
        $product = Product::with(['category'])
            ->where('id', $productId)
            ->where('is_active', true)
            ->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Get related products
        $relatedProducts = Product::with(['category'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->limit(4)
            ->get();

        return response()->json([
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ]);
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        if ($product->stock_quantity < $request->quantity) {
            return response()->json(['error' => 'Insufficient stock'], 400);
        }

        // Here you would implement cart logic (session, database, etc.)
        // For now, return success response
        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully'
        ]);
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'customer_info' => 'required|array',
            'payment_method' => 'required|in:card,mobile,bank',
            'cart_items' => 'required|array',
            'total_amount' => 'required|numeric|min:0'
        ]);

        // Here you would integrate with actual payment gateways
        // For Tanzania, you might integrate with:
        // - Stripe for international cards
        // - M-Pesa API for mobile money
        // - Bank transfer APIs
        
        try {
            // Create order
            $order = $this->createOrder($request);
            
            // Process payment based on method
            $paymentResult = $this->processPaymentByMethod($request, $order);
            
            if ($paymentResult['success']) {
                // Update stock
                $this->updateStock($request->cart_items);
                
                // Send confirmation email
                $this->sendOrderConfirmation($order);
                
                return response()->json([
                    'success' => true,
                    'order_id' => $order->id,
                    'message' => 'Payment processed successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $paymentResult['message']
                ], 400);
            }
        } catch (\Exception $e) {
            \Log::error('Payment processing error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed. Please try again.'
            ], 500);
        }
    }

    private function createOrder($request)
    {
        // Create order logic here
        // This would create an order in your database
        return [
            'id' => 'ORD' . time(),
            'status' => 'completed',
            'total' => $request->total_amount,
            'customer_info' => $request->customer_info
        ];
    }

    private function processPaymentByMethod($request, $order)
    {
        switch ($request->payment_method) {
            case 'card':
                return $this->processCardPayment($request, $order);
            case 'mobile':
                return $this->processMobilePayment($request, $order);
            case 'bank':
                return $this->processBankTransfer($request, $order);
            default:
                return ['success' => false, 'message' => 'Invalid payment method'];
        }
    }

    private function processCardPayment($request, $order)
    {
        // Integrate with Stripe or other card payment processor
        // For demo, return success
        return ['success' => true, 'message' => 'Card payment processed'];
    }

    private function processMobilePayment($request, $order)
    {
        // Integrate with M-Pesa, Tigo Pesa, etc.
        // For demo, return success
        return ['success' => true, 'message' => 'Mobile payment processed'];
    }

    private function processBankTransfer($request, $order)
    {
        // Handle bank transfer logic
        // For demo, return success
        return ['success' => true, 'message' => 'Bank transfer initiated'];
    }

    private function updateStock($cartItems)
    {
        foreach ($cartItems as $item) {
            Product::where('id', $item['product_id'])
                ->decrement('stock_quantity', $item['quantity']);
        }
    }

    private function sendOrderConfirmation($order)
    {
        // Send email confirmation logic here
        \Log::info('Order confirmation sent for order: ' . $order['id']);
    }

    private function getTotalCustomers()
    {
        // Get total unique customers from sales
        return Sale::where('status', 'completed')
            ->distinct('customer_id')
            ->count('customer_id');
    }

    private function getTotalSales()
    {
        return Sale::where('status', 'completed')
            ->sum('total') ?? 0;
    }

    private function getTestimonials()
    {
        // You can create a testimonials table or return static data
        return [
            [
                'id' => 1,
                'name' => 'Sarah Johnson',
                'avatar' => 'https://images.unsplash.com/photo-1494790108755-2616b332c1c3?w=100&h=100&fit=crop&crop=face',
                'comment' => 'Amazing shopping experience! Fast delivery and great customer service. Will definitely shop again!',
                'rating' => 5
            ],
            [
                'id' => 2,
                'name' => 'Michael Chen',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=face',
                'comment' => 'Great products at competitive prices. The website is easy to use and checkout process is smooth.',
                'rating' => 5
            ],
            [
                'id' => 3,
                'name' => 'Amina Hassan',
                'avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=100&h=100&fit=crop&crop=face',
                'comment' => 'Love the variety of products available. Customer support is very helpful and responsive.',
                'rating' => 5
            ]
        ];
    }
}
