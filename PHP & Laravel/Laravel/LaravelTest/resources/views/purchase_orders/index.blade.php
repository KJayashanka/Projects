@extends('layouts.app1')

@section('content')
    <h1>Purchase Orders</h1>
    <a href="{{ route('purchase_orders.create') }}" class="btn btn-primary">Add New PO</a>

    <table class="table">
        <thead>
            <tr>
                <th>PO Number</th>
                <th>Zone</th>
                <th>Region</th>
                <th>Territory</th>
                <th>Distributor</th>
                <th>Order Date</th>
                <th>Remark</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchaseOrders as $po)
                <tr>
                    <td>{{ $po->po_number }}</td>
                    <td>{{ $po->zone }}</td>
                    <td>{{ $po->region }}</td>
                    <td>{{ $po->territory }}</td>
                    <td>{{ $po->distributorUser->username }}</td>
                    <td>{{ $po->order_date }}</td>
                    <td>{{ $po->remark }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h1>View Purchase Orders</h1>

    <form method="post" action="{{ route('purchase_orders.filter') }}">
        @csrf

        <div class="form-group">
            <label for="region">Region:</label>
            <select class="form-control" name="region" id="region">
                <option value="" selected>Select Region</option>
                @foreach($regions as $region)
                    <option value="{{ $region }}">{{ $region }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="territory">Territory:</label>
            <select class="form-control" name="territory" id="territory">
                <option value="" selected>Select Territory</option>
                @foreach($territories as $territory)
                    <option value="{{ $territory }}">{{ $territory }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="po_number">PO Number:</label>
            <select class="form-control" name="po_number" id="po_number">
                <option value="" selected>Select PO Number</option>
                @foreach($poNumbers as $po_number)
                    <option value="{{ $po_number }}">{{ $po_number }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="from_date">From Date:</label>
            <input type="date" class="form-control" name="from_date" id="from_date">
        </div>

        <div class="form-group">
            <label for="to_date">To Date:</label>
            <input type="date" class="form-control" name="to_date" id="to_date">
        </div>

        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <!-- Display filtered purchase orders here -->
    @if(isset($filteredPurchaseOrders) && count($filteredPurchaseOrders) > 0)
    <table class="table">
        <thead>
            <tr>
                <th>Region</th>
                <th>Territory</th>
                <th>Distributor</th>
                <th>PO Number</th>
                <th>Order Date</th>
                <th>Remark</th>
            </tr>
        </thead>
        <tbody>
            @foreach($filteredPurchaseOrders as $purchaseOrder)
            <tr>
                <td>{{ $purchaseOrder->region }}</td>
                <td>{{ $purchaseOrder->territory }}</td>
                <td>{{ $purchaseOrder->distributor }}</td>
                <td>{{ $purchaseOrder->po_number }}</td>
                <td>{{ $purchaseOrder->order_date }}</td>
                <td>{{ $purchaseOrder->remark }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    @endif
</div>
@endsection
