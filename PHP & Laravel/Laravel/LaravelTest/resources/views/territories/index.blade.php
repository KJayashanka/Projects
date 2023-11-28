@extends('layouts.app')

@section('content')
    <h1>Territory List</h1>
    <a href="{{ route('territory.create') }}" class="btn btn-primary mb-3">Register Territory</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Zone</th>
                <th>Region</th>
                <th>Territory Code</th>
                <th>Territory Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($territories as $territory)
                <tr>
                    <td>{{ $territory->id }}</td>
                    <td>{{ $territory->zone->zone_code }}</td>
                    <td>{{ $territory->region->region_name }}</td>
                    <td>{{ $territory->territory_code }}</td>
                    <td>{{ $territory->territory_name }}</td>
                    <td>
                        <a href="{{ route('territory.edit', ['id' => $territory->id]) }}" class="btn btn-sm btn-primary">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
