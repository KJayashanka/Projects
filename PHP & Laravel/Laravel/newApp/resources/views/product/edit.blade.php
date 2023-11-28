@extends('layouts.app')

@section ('content')
<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('mystylesheet.css') }}">
</head>
<body>

    <!-- Edit Product Form -->
    <h2>Edit Product</h2>
    <form method="post" action="{{ url('/products/' . $product->id . '/update') }}">
        @csrf
        <label for="edited_product_name">Product Name:</label>
        <input type="text" id="edited_product_name" name="edited_product_name" value="{{ $product->product_name }}" required><br>
        <label for="edited_price">Price:</label>
        <input type="number" id="edited_price" name="edited_price" value="{{ $product->price }}" required><br>
        <label for="edited_expiry_date">Expiry Date:</label>
        <input type="date" id="edited_expiry_date" name="edited_expiry_date" value="{{ $product->expiry_date }}" required><br>
        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>

</body>
</html>
@endsection