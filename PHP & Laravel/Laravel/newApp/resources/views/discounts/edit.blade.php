
@extends('layouts.app')

@section('content')
    <h1>Edit Discount</h1>
    
    <form method="post" action="{{ url('/discounts/' . $discount->id . '/update') }}">
        @csrf
        @method('PUT')
        <label for="discount_label">Discount Label:</label>
        <input type="text" id="discount_label" name="discount_label" value="{{ $discount->discount_label }}" required><br>
        <label for="purchase_product">Purchase Product:</label>
        <select name="purchase_product">
            @foreach($products as $product)
                <option value="{{ $product }}" {{ $product === $discount->purchase_product ? 'selected' : '' }}>{{ $product }}</option>
            @endforeach
        </select><br>
        <label for="discount_amount">Discount Amount (%):</label>
        <input type="number" id="discount_amount" name="discount_amount" value="{{ $discount->discount_amount }}" required><br>
        <button type="submit" class="btn btn-primary">Update Discount</button>
    </form>
@endsection
