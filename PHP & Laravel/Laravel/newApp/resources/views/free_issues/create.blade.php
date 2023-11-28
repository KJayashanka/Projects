@extends('layouts.app')

@section('content')
    <h1>Free Issue Registration</h1>
    
    <form method="post" action="{{ route('free_issues.store') }}">
        @csrf
        <label for="free_issue_label">Free Issue Label:</label>
        <input type="text" id="free_issue_label" name="free_issue_label" required><br>
        <label for="issue_type">Issue Type:</label>
        <select name="issue_type">
            <option value="Flat">Flat</option>
            <option value="Multiple">Multiple</option>
        </select><br>
        <label for="purchase_product">Purchase Product:</label>
        <select id="purchase_product" name="purchase_product" onchange="setFreeProduct();">
            <option value>Select Product</option>
            @foreach($products as $product)
                <option value="{{ $product }}">{{ $product }}</option>
            @endforeach
        </select><br>
        <label for="free_product">Free Product:</label>
        <input type="text" id="free_product" name="free_product" required readonly><br>
        <label for="purchase_quantity">Purchase Quantity:</label>
        <input type="number" id="purchase_quantity" name="purchase_quantity" required value="" oninput="updateLowerLimit();"><br>
        <label for="free_quantity">Free Quantity:</label>
        <input type="number" id="free_quantity" name="free_quantity" required value=""><br>
        <label for="lower_limit">Lower Limit:</label>
        <input type="number" id="lower_limit" name="lower_limit" required value=""><br>
        <label for="upper_limit">Upper Limit:</label>
        <input type="number" id="upper_limit" name="upper_limit" required><br>
        <button type="submit" class="btn btn-primary">Register Free Issue</button>
    </form>
    
    <script>
        function setFreeProduct() {
            var purchaseProduct = document.getElementById("purchase_product");
            var freeProduct = document.getElementById("free_product");
            freeProduct.value = purchaseProduct.value;
        }

        function updateLowerLimit() {
            var purchaseQuantity = document.getElementById("purchase_quantity").value;
            var lowerLimit = document.getElementById("lower_limit");
            
            lowerLimit.value = purchaseQuantity;
        }

        function updateFreeQuantityLimit() {
            var purchaseProduct = document.getElementById("purchase_product");
            var issueType = purchaseProduct.options[purchaseProduct.selectedIndex].getAttribute("data-issue-type");
            var lowerLimit = purchaseProduct.options[purchaseProduct.selectedIndex].getAttribute("data-lower-limit");
            var upperLimit = purchaseProduct.options[purchaseProduct.selectedIndex].getAttribute("data-upper-limit");

            var freeQuantityInput = document.getElementById("free_quantity");
            var purchaseQuantity = document.getElementById("purchase_quantity").value;

            if (issueType === "Flat") {
                // Set free quantity to 1 for Flat issue type
                freeQuantityInput.value = "1";
            } else if (issueType === "Multiple") {
                // Calculate free quantity based on the purchase quantity, lower limit, and upper limit for Multiple issue type
                var freeQuantity = Math.floor(purchaseQuantity / upperLimit);
                freeQuantityInput.value = Math.max(0, freeQuantity); // Ensure free quantity is non-negative
            }
        }
    </script>
@endsection
