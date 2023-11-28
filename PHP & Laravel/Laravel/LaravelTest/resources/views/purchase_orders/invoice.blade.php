<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        /* Add your CSS styles here for formatting the invoice */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #333;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }
</head>
<body>
    <div class="container">
        <h1>Invoice</h1>

        <p><strong>PO Number:</strong> {{ $purchaseOrder->po_number }}</p>
        <p><strong>Region:</strong> {{ $purchaseOrder->region }}</p>
        <p><strong>Territory:</strong> {{ $purchaseOrder->territory }}</p>
        <p><strong>Distributor:</strong> {{ $purchaseOrder->distributor }}</p>
        <p><strong>Order Date:</strong> {{ $purchaseOrder->order_date }}</p>
        <p><strong>Remark:</strong> {{ $purchaseOrder->remark }}</p>

        <!-- Add a table for listing purchased products -->
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>SKU Code</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchaseOrder->purchaseOrderLines as $line)
                    <tr>
                        <td>{{ $line->sku_name }}</td>
                        <td>{{ $line->sku_code }}</td>
                        <td>{{ $line->distributor_price }}</td>
                        <td>{{ $line->units }}</td>
                        <td>{{ $line->total_price }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
