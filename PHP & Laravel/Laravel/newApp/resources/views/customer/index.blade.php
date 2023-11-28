@extends('layouts.app')

@section ('content')
<!DOCTYPE html>
<html>
<head>
    <title>Customer Registration</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('mystylesheet.css') }}">
</head>
<body>
    <h1>Customer Registration</h1>

    <!-- Display success message if any -->
    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <!-- Customer Registration Form -->
    <form method="post" action="{{ url('/customers') }}">
        @csrf
        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" required><br>
        <label for="customer_address">Customer Address:</label>
        <input type="text" id="customer_address" name="customer_address" required><br>
        <label for="customer_contact">Customer Contact (10 digits):</label>
        <input type="text" id="customer_contact" name="customer_contact" required maxlength="10"><br>
        <button type="submit" class="btn btn-primary">Register Customer</button>
    </form>

    <!-- Display Registered Customers in a Table -->
    @if($customers->count() > 0)
        <h2>Registered Customers</h2>
        <table border="1">
            <tr>
                <th>Customer Code</th>
                <th>Customer Name</th>
                <th>Customer Address</th>
                <th>Customer Contact</th>
                <th>Edit</th>
            </tr>
            @foreach($customers as $customer)
                <tr>
                    <td>{{ $customer->customer_code }}</td>
                    <td>{{ $customer->customer_name }}</td>
                    <td>{{ $customer->customer_address }}</td>
                    <td>{{ $customer->customer_contact }}</td>
                    <td><a href="{{ route('customers.edit', ['id' => $customer->id]) }}">Edit</a></td>
                </tr>
            @endforeach
        </table>
    @endif

</body>
</html>
@endsection