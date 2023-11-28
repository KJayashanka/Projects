@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders</title>
    <!-- Include your head content -->
</head>
<body>
    <h1>View Orders</h1>
    @if (count($orders) > 0)
        <table border="1">
            <tr>
                <th>Order Number</th>
                <th>Customer Name</th>
                <th>Order Date</th>
                <th>Order Time</th>
                <th>Net Amount</th>
                <th>Details</th>
            </tr>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->order_date }}</td>
                    <td>{{ $order->order_time }}</td>
                    <td>{{ number_format($order->net_amount, 2) }}</td>
                    <td><a href="{{ route('orders.view_order_details', ['orderNumber' => $order->order_number]) }}">Details</a></td>
                </tr>
            @endforeach
        </table>
    @else
        <p>No orders found.</p>
    @endif
</body>
</html>
@endsection