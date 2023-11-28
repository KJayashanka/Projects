@extends('layouts.app')

@section('content')
    <h1>Place Order</h1>

    <form method="post" action="{{ route('orders.place_order') }}">
        @csrf
        <label for="customer_name">Customer Name:</label>
        <select name="customer_name">
            <option value="">Select Customer</option>
            @foreach($customerOptions as $customerId => $customerName)
                <option value="{{ $customerName }}">{{ $customerName }}</option>
            @endforeach
        </select><br>

        <label for="order_number">Order Number:</label>
        <input type="text" name="order_number" value="{{ $orderNumber }}" readonly>

        <h2>Products</h2>

        <table border="1">
            <tr>
                <th>Select</th>
                <th>Product Name</th>
                <th>Product Code</th>
                <th>Price</th>
                <th>Purchase Quantity</th>
                <th>Discount (%)</th>
                <th>Free Quantity</th>
                <th>Amount</th>
            </tr>

            @foreach($products as $product)
                <tr>
                    <td><input type='checkbox' name='id[]' value='{{ $product->id }}'></td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->product_code }}</td>
                    <td>{{ $product->price }}</td>
                    <td><input type='number' name='purchase_quantity[]' value='0' min='0' oninput='calculateAmount(this, {{ $product->lower_limit }})'></td>
                    <td><input type='number' name='discount[]' value='{{ $product->discount }}' min='0' max='100' readonly></td>               
                    <td><span class='free-quantity'>0</span></td>
                    <td><span class='amount'>0.00</span></td>
                    <input type='hidden' name='product_code[]' value='{{ $product->id }}'>
                    <input type='hidden' name='lower_limit[]' value='{{ $product->lower_limit }}'>
                    <input type='hidden' name='upper_limit[]' value='{{ $product->upper_limit }}'>
                </tr>
            @endforeach

            <tr>
                <td colspan="7" align="right">Net Amount:</td>
                <td><span id="net_amount">0.00</span></td>
            </tr>
        </table>

        <button type="submit" name="place_order" class="btn btn-primary">Place Order</button>
        <a href="{{ route('orders.view_orders') }}"><button type="button" class="btn btn-primary">View Orders</button></a>
    </form>

    <script>
       function calculateAmount(input) {
        var row = input.parentElement.parentElement;
        var priceCell = row.cells[3];
        var purchaseQuantityCell = row.cells[4];
        var discountCell = row.cells[5];
        var freeQuantityCell = row.cells[6];
        var amountCell = row.cells[7];

        var price = parseFloat(priceCell.textContent);
        var purchaseQuantity = parseFloat(input.value);
        var discount = parseFloat(discountCell.querySelector("input").value);
        var lowerLimit = parseFloat(row.querySelector('input[name="lower_limit[]"]').value);
        var upperLimit = parseFloat(row.querySelector('input[name="upper_limit[]"]').value);

        var freeQuantity = 0;

        if (lowerLimit > 0 && purchaseQuantity >= lowerLimit && purchaseQuantity <= upperLimit) {
            freeQuantity = Math.floor(purchaseQuantity / lowerLimit);
        } else if (purchaseQuantity > upperLimit) {
            freeQuantity = Math.floor(upperLimit / lowerLimit);
        }

        freeQuantityCell.querySelector(".free-quantity").textContent = freeQuantity;

        var totalAmount = (price * purchaseQuantity * (1 - discount / 100)).toFixed(2);
        amountCell.querySelector(".amount").textContent = totalAmount;

        calculateNetAmount();
    }


        function calculateNetAmount() {
            var purchaseQuantities = document.querySelectorAll('input[name="purchase_quantity[]"]');
            var amountCells = document.querySelectorAll('.amount');
            var netAmount = 0;

            purchaseQuantities.forEach(function (purchaseQuantity, index) {
                var quantity = parseFloat(purchaseQuantity.value);
                var discount = parseFloat(purchaseQuantity.closest("tr").cells[5].querySelector("input").value);
                var price = parseFloat(purchaseQuantity.closest("tr").cells[3].textContent);
                var totalAmount = price * quantity * (1 - (discount / 100));
                netAmount += totalAmount;
            });

            var netAmountCell = document.getElementById("net_amount");
            netAmountCell.textContent = netAmount.toFixed(2);
        }
    </script>
@endsection