<!DOCTYPE html>
<html>
<head>
    <title>Selected Invoices</title>
    <style>
        /* Add your CSS styles here for formatting the page */
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

        /* Add more styles as needed */
    </style>
</head>
<body>
    <div class="container">
        <h1>Selected Invoices</h1>

        <form method="post" action="{{ route('purchase_orders.generate_invoices') }}">
            @csrf

            <!-- Table for listing selected purchase orders with checkboxes -->
            <table>
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>PO Number</th>
                        <th>Region</th>
                        <th>Territory</th>
                        <th>Distributor</th>
                        <th>Order Date</th>
                        <th>Remark</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($selectedPurchaseOrders as $purchaseOrder)
                        <tr>
                            <td>
                                <input type="checkbox" name="selected_invoices[]" value="{{ $purchaseOrder->po_number }}" checked>
                            </td>
                            <td>{{ $purchaseOrder->po_number }}</td>
                            <td>{{ $purchaseOrder->region }}</td>
                            <td>{{ $purchaseOrder->territory }}</td>
                            <td>{{ $purchaseOrder->distributor }}</td>
                            <td>{{ $purchaseOrder->order_date }}</td>
                            <td>{{ $purchaseOrder->remark }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary">Generate Invoices</button>
        </form>
    </div>
</body>
</html>
