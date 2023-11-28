@extends('layouts.app')

@section('content')
    <h1>Region List</h1>
    <a href="{{ route('region.create') }}" class="btn btn-primary mb-3">Register Region</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Zone</th>
                <th>Region Code</th>
                <th>Region Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($regions as $region)
                <tr>
                    <td>{{ $region->id }}</td>
                    <td>{{ optional($region->zone)->zone_code }}</td>
                    <td>{{ $region->region_code }}</td>
                    <td>{{ $region->region_name }}</td>
                    <td>
                        <a href="{{ route('region.edit', ['id' => $region->id]) }}" class="btn btn-sm btn-primary">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
