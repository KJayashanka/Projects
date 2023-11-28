@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Product Registration</h2>
        <form action="{{ route('products.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="sku_code">SKU Code:</label>
                <input type="text" name="sku_code" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="sku_name">SKU Name:</label>
                <input type="text" name="sku_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="distributor_price">Distributor Price:</label>
                <input type="text" name="distributor_price" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="mrp">MRP:</label>
                <input type="text" name="mrp" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="units">Units:</label>
                <input type="number" name="units" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="measure">Measure:</label>
                <select name="measure" class="form-control" required>
                    <option value="ml">ml</option>
                    <option value="g">g</option>
                    <option value="kg">kg</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save Product</button>
        </form>
    </div>
@endsection
