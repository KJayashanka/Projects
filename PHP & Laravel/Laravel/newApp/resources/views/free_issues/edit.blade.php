@extends('layouts.app')

@section('content')
    <h1>Edit Free Issue</h1>
    
    <form method="post" action="{{ route('free_issues.update', $freeIssue->id) }}">
        @csrf
        @method('PUT')
        <label for="free_issue_label">Free Issue Label:</label>
        <input type="text" id="free_issue_label" name="free_issue_label" value="{{ $freeIssue->free_issue_label }}" required><br>
        <label for="issue_type">Issue Type:</label>
        <select name="issue_type">
            <option value="Flat" {{ $freeIssue->issue_type === 'Flat' ? 'selected' : '' }}>Flat</option>
            <option value="Multiple" {{ $freeIssue->issue_type === 'Multiple' ? 'selected' : '' }}>Multiple</option>
        </select><br>
        <label for="purchase_product">Purchase Product:</label>
        <select id="purchase_product" name="purchase_product" onchange="setFreeProductInEditForm();">
            @foreach($products as $product)
                <option value="{{ $product }}" {{ $product === $freeIssue->purchase_product ? 'selected' : '' }}>{{ $product }}</option>
            @endforeach
        </select><br>
        <label for="free_product">Free Product:</label>
        <input type="text" id="free_product_edit" name="free_product" value="{{ $freeIssue->free_product }}" required><br>
        <label for="purchase_quantity">Purchase Quantity:</label>
        <input type="number" id="purchase_quantity_edit" name="purchase_quantity" value="{{ $freeIssue->purchase_quantity }}" oninput="updateLowerLimit();" required><br>
        <label for="free_quantity">Free Quantity:</label>
        <input type="number" id="free_quantity_edit" name="free_quantity" value="{{ $freeIssue->free_quantity }}" required><br>
        <label for="lower_limit">Lower Limit:</label>
        <input type="number" id="lower_limit" name="lower_limit" value="{{ $freeIssue->lower_limit }}" required><br>
        <label for="upper_limit">Upper Limit:</label>
        <input type="number" id="upper_limit" name="upper_limit" value="{{ $freeIssue->upper_limit }}" required><br>
        <button type="submit" class="btn btn-primary">Update Free Issue</button>
    </form>
    
    <script>
        function setFreeProductInEditForm() {
            var purchaseProductEdit = document.getElementById("purchase_product");
            var freeProductEdit = document.getElementById("free_product_edit");
            freeProductEdit.value = purchaseProductEdit.value;
            updateLowerLimit(); // Call the function to update lower limit when purchase product changes
        }

        function updateLowerLimit() {
            var purchaseQuantityEdit = document.getElementById("purchase_quantity_edit").value;
            var lowerLimitEdit = document.getElementById("lower_limit");

            // This is just a basic example, you may want to use a more complex formula
            lowerLimitEdit.value = purchaseQuantityEdit; // Update the lower limit based on the purchase quantity
        }
    </script>
@endsection

