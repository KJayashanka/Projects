@extends('layouts.app')

@section('content')
    <h1>Zone List</h1>
    <a href="{{ route('zone.create') }}" class="btn btn-primary">Register Zone</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Zone Code</th>
                <th>Long Description</th>
                <th>Short Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($zones as $zone)
                <tr>
                    <td>{{ $zone->id }}</td>
                    <td>{{ $zone->zone_code }}</td>
                    <td>{{ $zone->long_description }}</td>
                    <td>{{ $zone->short_description }}</td>
                    <td>
                        <a href="{{ route('zone.edit', $zone->id) }}" class="btn btn-primary">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
