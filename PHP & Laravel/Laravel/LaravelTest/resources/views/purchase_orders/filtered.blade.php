@extends('layouts.app1')

@section('content')
<div class="container">
    <h1>Filtered Purchase Orders</h1>

    <!-- Add the filter form here -->
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

    @if (count($filteredPurchaseOrders) > 0)
        <form method="post" action="{{ route('purchase_orders.generate_invoices') }}">
            @csrf
            <table class="table">
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>Region</th>
                        <th>Territory</th>
                        <th>Distributor</th>
                        <th>PO Number</th>
                        <th>Order Date</th>
                        <th>Remark</th>
                        <th>View Invoice</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($filteredPurchaseOrders as $purchaseOrder)
                        <tr>
                            <td>
                                <input type="checkbox" name="selected_invoices[]" value="{{ $purchaseOrder->po_number }}">
                            </td>
                            <td>{{ $purchaseOrder->region }}</td>
                            <td>{{ $purchaseOrder->territory }}</td>
                            <td>{{ $purchaseOrder->distributor }}</td>
                            <td>{{ $purchaseOrder->po_number }}</td>
                            <td>{{ $purchaseOrder->order_date }}</td>
                            <td>{{ $purchaseOrder->remark }}</td>
                            <td>
                                <a href="{{ route('purchase_orders.view_invoice', ['po_number' => $purchaseOrder->po_number]) }}" target="_blank">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Generate Invoices</button>
        </form>
    @else
        <p>No matching purchase orders found.</p>
    @endif
</div>
@endsection
