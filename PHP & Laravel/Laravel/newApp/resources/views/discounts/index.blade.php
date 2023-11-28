
@extends('layouts.app')

@section('content')
    <h1>Discounts Registration</h1>

    <form method="post" action="{{ route('discounts.store') }}">
        @csrf
        <label for="discount_label">Discount Label:</label>
        <input type="text" id="discount_label" name="discount_label" required><br>
        <label for="purchase_product">Purchase Product:</label>
        <select name="purchase_product">
        <option value>Select Product</option>
            @foreach($products as $product)
                <option value="{{ $product }}">{{ $product }}</option>
            @endforeach
        </select><br>
        <label for="discount_amount">Discount Amount (%):</label>
        <input type="number" id="discount_amount" name="discount_amount" required><br>
        <button type="submit" class="btn btn-primary">Register Discount</button>
    </form>

    <h1>Display Discounts</h1>
    <table border="1">
        <tr>
            <th>Discount Label</th>
            <th>Purchase Product</th>
            <th>Discount Amount (%)</th>
            <th>Edit</th>
        </tr>
        @foreach($discounts as $discount)
            <tr>
                <td>{{ $discount->discount_label }}</td>
                <td>{{ $discount->purchase_product }}</td>
                <td>{{ $discount->discount_amount }}</td>
                <td><a href="{{ route('discounts.edit', $discount->id) }}">Edit</a></td>
            </tr>
        @endforeach
    </table>
@endsection
