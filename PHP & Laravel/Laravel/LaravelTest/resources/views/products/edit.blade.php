@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Product</h2>
        <form action="{{ route('products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="sku_code">SKU Code:</label>
                <input type="text" name="sku_code" value="{{ $product->sku_code }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="sku_name">SKU Name:</label>
                <input type="text" name="sku_name" value="{{ $product->sku_name }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="distributor_price">Distributor Price:</label>
                <input type="text" name="distributor_price" value="{{ $product->distributor_price }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="mrp">MRP:</label>
                <input type="text" name="mrp" value="{{ $product->mrp }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="units">Units:</label>
                <input type="number" name="units" value="{{ $product->units }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="measure">Measure:</label>
                <select name="measure" class="form-control" required>
                    <option value="ml" {{ $product->measure === 'ml' ? 'selected' : '' }}>ml</option>
                    <option value="g" {{ $product->measure === 'g' ? 'selected' : '' }}>g</option>
                    <option value="kg" {{ $product->measure === 'kg' ? 'selected' : '' }}>kg</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
    </div>
@endsection
