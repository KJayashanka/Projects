@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Product List</h2>
        <a href="{{ route('products.create') }}" class="btn btn-primary">Add New Product</a>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>SKU Code</th>
                    <th>SKU Name</th>
                    <th>Distributor Price</th>
                    <th>MRP</th>
                    <th>Units</th>
                    <th>Measure</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->sku_code }}</td>
                        <td>{{ $product->sku_name }}</td>
                        <td>{{ $product->distributor_price }}</td>
                        <td>{{ $product->mrp }}</td>
                        <td>{{ $product->units }}</td>
                        <td>{{ $product->measure }}</td>
                        <td>
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
