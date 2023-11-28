@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <!-- Include your head content -->
</head>
<body>
    <h1>Order Details</h1>
    @if (isset($orderDetails) && count($orderDetails) > 0)
        <table border="1">
            <tr>
                <th>Order Number</th>
                <th>Customer Name</th>
                <th>Product Name</th>
                <th>Product Code</th>
                <th>Price</th>
                <th>Discount (%)</th>
                <th>Purchase Quantity</th>
                <th>Free Quantity</th>
                <th>Amount</th>
            </tr>
            @php
                $netAmount = 0; // Initialize net amount
            @endphp
            @foreach ($orderDetails as $order)
                @php
                    $price = $order->price;
                    $discount = $order->discount;
                    $purchaseQuantity = $order->purchase_quantity;
                    $amount = $price * $purchaseQuantity * ((100 - $discount) / 100); // Calculate the discount amount
                    $netAmount += $amount; // Accumulate the amount for net amount
                @endphp
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->product_name }}</td>
                    <td>{{ $order->product_code }}</td>
                    <td>{{ $price }}</td>
                    <td>{{ $discount }}</td>
                    <td>{{ $purchaseQuantity }}</td>
                    <td>{{ $order->free_quantity }}</td>
                    <td>{{ number_format($amount, 2) }}</td>
                </tr>
            @endforeach
            <!-- Add the total row with the net amount -->
            <tr>
                <td colspan="8">Net Amount:</td>
                <td>{{ number_format($netAmount, 2) }}</td>
            </tr>
        </table>
    @else
        <p>Order not found.</p>
    @endif
</body>
</html>
@endsection