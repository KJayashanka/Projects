
@extends('layouts.app')

@section('content')
    <h1>Free Issues</h1>
    <a href="{{ route('free_issues.create') }}" class="btn btn-primary">Add Free issue</a>
    <table border="1">
        <tr>
            <th>Free Issue Label</th>
            <th>Issue Type</th>
            <th>Purchase Product</th>
            <th>Free Product</th>
            <th>Purchase Quantity</th>
            <th>Free Quantity</th>
            <th>Lower Limit</th>
            <th>Upper Limit</th>
            <th>Edit</th>
        </tr>
        @foreach($freeIssues as $freeIssue)
            <tr>
                <td>{{ $freeIssue->free_issue_label }}</td>
                <td>{{ $freeIssue->issue_type }}</td>
                <td>{{ $freeIssue->purchase_product }}</td>
                <td>{{ $freeIssue->free_product }}</td>
                <td>{{ $freeIssue->purchase_quantity }}</td>
                <td>{{ $freeIssue->free_quantity }}</td>
                <td>{{ $freeIssue->lower_limit }}</td>
                <td>{{ $freeIssue->upper_limit }}</td>
                <td><a href="{{ route('free_issues.edit', $freeIssue->id) }}">Edit</a></td>
            </tr>
        @endforeach
    </table>
@endsection
