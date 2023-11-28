<!DOCTYPE html>
<html>
<head>
    <title>View Invoices</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    <iframe src="{{ route('purchase_orders.generateInvoices') }}" frameborder="0"></iframe>
</body>
</html>
