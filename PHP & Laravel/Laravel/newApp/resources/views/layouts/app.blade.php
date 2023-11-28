<!-- resources/views/layouts/app.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ceylon Test</title>

    <!-- Add your CSS and JavaScript links here -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body>
    <div id="navbar">
        <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container">
                    <a class="navbar-brand" href="{{ route('customers.index') }}">Add Customer</a>
                    <a class="navbar-brand" href="{{ route('products.index') }}">Add Products</a>
                    <a class="navbar-brand" href="{{ route('free_issues.index') }}">Add Free Issue</a>
                    <a class="navbar-brand" href="{{ route('discounts.index') }}">Add Discount</a>
                    <a class="navbar-brand" href="{{ route('orders.place_order') }}">Place Order</a> <!-- Add this line -->
                    <a class="navbar-brand" href="{{ route('orders.view_orders') }}">View Orders</a>
                    <!-- Add your navigation links or menu here -->
                </div>
            </nav>
        </header>
    
        <main role="main" class="container">
            @yield('content') <!-- This is where the specific content will be inserted -->
        </main>

        <footer class="text-center mt-5">
            <p>&copy; {{ date('Y') }} All rights reserved.</p>
        </footer>

        <!-- Add your JavaScript scripts here -->
        <script src="{{ asset('js/app.js') }}"></script>
        <!-- Add more scripts as needed -->
    </div>
</body>
</html>
