@extends('layouts.app')

@section ('content')
<!DOCTYPE html>
<html>
<head>
    <title>Edit Customer</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('mystylesheet.css') }}">
</head>
<body>

    <!-- Edit Customer Form -->
    <h2>Edit Customer</h2>
    <form method="post" action="{{ url('/customers/' . $customer->id . '/update') }}">
        @csrf
        <label for="edited_customer_name">Customer Name:</label>
        <input type="text" id="edited_customer_name" name="edited_customer_name" value="{{ $customer->customer_name }}" required><br>
        <label for="edited_customer_address">Customer Address:</label>
        <input type="text" id="edited_customer_address" name="edited_customer_address" value="{{ $customer->customer_address }}" required><br>
        <label for="edited_customer_contact">Customer Contact:</label>
        <input type="text" id="edited_customer_contact" name="edited_customer_contact" value="{{ $customer->customer_contact }}" required><br>
        <button type="submit" class="btn btn-primary">Update Customer</button>
    </form>

</body>
</html>
@endsection
