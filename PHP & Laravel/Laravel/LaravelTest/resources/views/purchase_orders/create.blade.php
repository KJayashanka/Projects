@extends('layouts.app1')

@section('content')
    <h1>Individual Purchase Order</h1>

    <form method="POST" action="{{ route('purchase_orders.store') }}">
        @csrf
        <div class="form-group">
            <label for="zone">Zone</label>
            <select name="zone" id="zone" class="form-control">
                <option value="">Select Zone</option>
                @foreach($zones as $zone)
                    <option value="{{ $zone->id }}">{{ $zone->short_description }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="region">Region</label>
            <select name="region" id="region" class="form-control" disabled>
                <option value="">Select Region</option>
            </select>
        </div>
        <div class="form-group">
            <label for="territory">Territory</label>
            <select name="territory" id="territory" class="form-control" disabled>
                <option value="">Select Territory</option>
            </select>
        </div>
        <div class="form-group">
            <label for="distributor">Distributor</label>
            <select name="distributor" id="distributor" class="form-control" disabled>
                <option value="">Select Distributor</option>
            </select>
        </div>

        <div class="form-group">
            <label for="date">Date:</label>
            <input type="text" name="date" value="<?php echo date('Y-m-d'); ?>" readonly><br>
        </div>

        <div class="form-group">
            <label for="po_number">PO Number</label>
            <input type="text" name="po_number" class="form-control" value="{{ $poNumber }}" readonly>
        </div>

        <div class="form-group">
            <label for="remark">Remark</label>
            <input type="text" class="form-control" id="remark" name="remark" required>
        </div>
            <h2>Product Details</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Add to PO</th>
                        <th>SKU Code</th>
                        <th>SKU Name</th>
                        <th>Distributor Price</th>
                        <th>Unit Price (MRP)</th>
                        <th>Units</th>
                        <th>Total Price</th>
                        <!-- Add more columns for product details as needed -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr>
                        <td><input type="checkbox" name="products[]" value="{{ $product->sku_code }}" onclick="populateHiddenFields(this)"></td>
                        <td>{{ $product->sku_code }}</td>
                        <td>{{ $product->sku_name }}</td>
                        <td>{{ $product->distributor_price }}</td>
                        <td>{{ $product->mrp }}</td>
                        <td>
                        <input type="number" name="unit_amount[]" value="1" min="1" oninput="calculateTotalPrice(this, {{ $product->mrp }})">
                        </td>
                        <td class="total_price">{{ $product->mrp }}</td>

                        <input type="hidden" name="sku_code[]" value="{{ $product->sku_code }}">
                        <input type="hidden" name="sku_name[]" value="{{ $product->sku_name }}">
                        <input type="hidden" name="distributor_price[]" value="{{ $product->distributor_price }}">
                        <input type="hidden" name="mrp[]" value="{{ $product->mrp }}">
                        <input type="hidden" name="units[]" value="{{ $product->units }}">
                        <!-- Add more input fields for additional product details -->
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>

    <script>
    function calculateTotalPrice(input, unitPrice) {
        const totalRow = input.parentNode.parentNode.querySelector('.total_price');
        const units = input.value;
        totalRow.textContent = (unitPrice * units).toFixed(2); // Update total price
    }
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Zone selection change
        $('#zone').on('change', function () {
            let zoneId = $(this).val();
            if (zoneId) {
                fetchRegions(zoneId);
            } else {
                resetSelect('#region');
                resetSelect('#territory');
                resetSelect('#distributor');
            }
        });

        // Region selection change
        $('#region').on('change', function () {
            let regionId = $(this).val();
            if (regionId) {
                fetchTerritories(regionId);
            } else {
                resetSelect('#territory');
                resetSelect('#distributor');
            }
        });

        // Territory selection change
        $('#territory').on('change', function () {
            let territoryId = $(this).val();
            if (territoryId) {
                fetchDistributors(territoryId);
            } else {
                resetSelect('#distributor');
            }
        });

        // Fetch Regions based on Zone
        function fetchRegions(zoneId) {
            $.get('/fetchRegions/' + zoneId, function (data) {
                updateSelect('#region', data);
            });
        }

        // Fetch Territories based on Region
        function fetchTerritories(regionId) {
            $.get('/fetchTerritories/' + regionId, function (data) {
                updateSelect('#territory', data);
            });
        }

        // Fetch Distributors based on Territory
        function fetchDistributors(territoryId) {
            $.get('/fetchDistributors/' + territoryId, function (data) {
                updateSelect('#distributor', data);
            });
        }

        // Helper function to update a select element
        function updateSelect(selectId, data) {
            const select = $(selectId);
            select.empty();
            select.append('<option value="">Select</option>');
            if (data) {
                $.each(data, function (key, value) {
                    select.append($('<option>', {
                        value: key,
                        text: value
                    }));
                });
                select.prop('disabled', false);
            } else {
                console.log('No data received or an error occurred.');
            }
        }

        // Helper function to reset a select element
        function resetSelect(selectId) {
            const select = $(selectId);
            select.empty();
            select.append('<option value="">Select</option>');
            select.prop('disabled', true);
        }
    });
</script>
</html>
@endsection

