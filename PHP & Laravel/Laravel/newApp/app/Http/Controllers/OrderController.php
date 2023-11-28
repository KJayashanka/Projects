<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\FreeIssue;

class OrderController extends Controller
{
    public function showPlaceOrderForm()
    {
        $customerOptions = Customer::pluck('customer_name', 'id');
        $orderNumber = $this->generateOrderNumber();

        $products = Product::leftJoin('free_issues', 'products.id', '=', 'free_issues.purchase_product_id')
            ->select('products.id', 'products.product_name', 'products.product_code', 'products.price', 'products.discount', 'free_issues.lower_limit', 'free_issues.upper_limit')
            ->get();

        return view('orders.place_order', [
            'customerOptions' => $customerOptions,
            'orderNumber' => $orderNumber,
            'products' => $products,
        ]);
    }

    public function placeOrder(Request $request)
    {
        // Validate the form data
        $request->validate([
            'customer_name' => 'required',
            'order_number' => 'required',
            'id' => 'required|array',
            'purchase_quantity' => 'required|array',
            'discount' => 'required|array',
        ]);

        $customer_name = $request->input('customer_name');
        $order_number = $request->input('order_number');
        $product_ids = $request->input('id');
        $purchase_quantities = $request->input('purchase_quantity');
        $product_codes = $request->input('product_code');
        $discounts = $request->input('discount');

        $net_amount = 0; // Initialize the net amount

        // Get the current date and time
        $order_date = now(); // Use the current date and time

        // Insert order details into the database
        foreach ($product_ids as $key => $product_id) {
            $purchase_quantity = $purchase_quantities[array_search($product_id, $product_codes)];
            $discount = $discounts[array_search($product_id, $product_codes)];

            // Fetch product details
            $product = Product::find($product_id);

            if ($product) {
                // Calculate the free quantity based on the free issues table
                $free_quantity = $this->calculateFreeQuantity($product_id, $purchase_quantity);

                // Calculate the total amount for the order, including the discount
                $total_amount = $product->price * $purchase_quantity * (1 - ($discount / 100));

                // Insert the order details into the orders table
                $order = Order::create([
                    'customer_name' => $customer_name,
                    'order_number' => $order_number,
                    'product_name' => $product->product_name,
                    'product_code' => $product->product_code,
                    'price' => $product->price,
                    'purchase_quantity' => $purchase_quantity,
                    'free_quantity' => $free_quantity,
                    'amount' => $total_amount,
                    // 'order_date' => $order_date,
                    // 'order_time' => $order_date,
                    'discount' => $discount,
                    'net_amount' => $net_amount,
                ]);

                $net_amount += $total_amount; // Update the net amount
            }
        }

        // Update the net amount in the orders table
        Order::where('order_number', $order_number)->update(['net_amount' => $net_amount]);

        // Redirect or perform additional actions as needed
        return redirect()->route('orders.place_order')->with('success', 'Order placed successfully!');
    }

    public function viewOrders()
    {
        $orders = Order::select('order_number', 'customer_name', 'order_date', 'order_time','net_amount')
            ->groupBy('order_number', 'customer_name', 'order_date', 'order_time','net_amount')
            ->get();

        return view('orders.view_orders', compact('orders'));
    }

    public function viewOrderDetails($order_number)
    {
        $orderDetails = Order::where('order_number', $order_number)->get();

        return view('orders.view_order_details', compact('orderDetails'));
    }

    private function generateOrderNumber()
    {
        $order_number = "OD" . rand(1000, 9999);

        while (Order::where('order_number', $order_number)->exists()) {
            $order_number = "OD" . rand(1000, 9999);
        }

        return $order_number;
    }

    private function calculateFreeQuantity($product_id, $purchase_quantity)
    {
        // Fetch free issues data for the given product
        $free_issues = FreeIssue::where('purchase_product_id', $product_id)->first();
    
        if ($free_issues) {
            $purchase_quantity_needed = $free_issues->purchase_quantity;
    
            if ($purchase_quantity_needed > 0) {
                // Calculate the free quantity based on the purchase quantity and the purchase quantity needed for free items
                $free_quantity = floor($purchase_quantity / $purchase_quantity_needed);
    
                return $free_quantity;
            }
        }
    
        return 0; // Default to 0 free quantity if no matching record is found or purchase_quantity_needed is not positive
    }
}
