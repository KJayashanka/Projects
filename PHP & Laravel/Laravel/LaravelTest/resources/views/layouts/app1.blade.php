<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Distributor Login</title>
    
    
    <!-- Add your CSS and JavaScript links here -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body>
    <div id = "navbar">
        <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container">
                    <a class="navbar-brand" href="{{ route('zone.index') }}">Add Zone</a>
                    <a class="navbar-brand" href="{{ route('regions.index') }}">Add Region</a>
                    <a class="navbar-brand" href="{{ route('territories.index') }}">Add Territory</a>
                    <a class="navbar-brand" href="{{ route('user.index') }}">Add User</a>
                    <a class="navbar-brand" href="{{ route('products.index') }}">Add Products</a>

                    
                    <a class="navbar-brand" href="{{ route('purchase_orders.index') }}">Add PO</a>
                    <!-- Add your navigation links or menu here -->
                </div>
            </nav>
        </div>
    </header>
    
    <main role="main" class="container">
        @yield('content')
    </main>

    <footer class="text-center mt-5">
        <p>&copy; {{ date('Y') }} User Distributor Login. All rights reserved.</p>
    </footer>

    <!-- Add your JavaScript scripts here -->
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- Add more scripts as needed -->
</body>
</html>
