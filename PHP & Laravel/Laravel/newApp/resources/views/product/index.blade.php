@extends('layouts.app')

@section ('content')
<!DOCTYPE html>
<html>
<head>
    <title>Product Registration</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('mystylesheet.css') }}">
</head>
<body>
    <h1>Product Registration</h1>

    <!-- Display success message if any -->
    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <!-- Product Registration Form -->
    <form method="post" action="{{ url('/products') }}">
        @csrf
        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" required><br>
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" required><br>
        <label for="expiry_date">Expiry Date:</label>
        <input type="date" id="expiry_date" name="expiry_date" required><br>
        <button type="submit" class="btn btn-primary">Register Product</button>
    </form>

    <!-- Display Registered Products in a Table -->
    @if($products->count() > 0)
        <h2>Registered Products</h2>
        <table border="1">
            <tr>
                <th>Product Code</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Expiry Date</th>
                <th>Edit</th>
            </tr>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->product_code }}</td>
                    <td>{{ $product->product_name }}</td>
                    <td>${{ $product->price }}</td>
                    <td>{{ $product->expiry_date }}</td>
                    <td><a href="{{ url('/products/' . $product->id . '/edit') }}">Edit</a></td>
                </tr>
            @endforeach
        </table>
    @endif

</body>
</html>
@endsection