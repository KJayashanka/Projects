@extends('layouts.app')

@section('content')
    <h1>Edit Territory</h1>
    <form method="post" action="{{ route('territory.update', ['territory' => $territory->id]) }}">
        @csrf
        @method('PATCH')
        <div class="form-group">
            <label for="zone_id">Select Zone:</label>
            <select name="zone_id" class="form-control">
                @foreach ($zones as $zone)
                    <option value="{{ $zone['id'] }}" {{ $territory->zone_id == $zone['id'] ? 'selected' : '' }}>{{ $zone['zone_code'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="region_id">Select Region:</label>
            <select name="region_id" class="form-control">
                @foreach ($regions as $region)
                    <option value="{{ $region['id'] }}" {{ $territory->region_id == $region['id'] ? 'selected' : '' }}>{{ $region['region_name'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="territory_code">Territory Code:</label>
            <input type="text" name="territory_code" value="{{ $territory->territory_code }}" class="form-control" readonly>
        </div>
        <div class="form-group">
            <label for="territory_name">Territory Name:</label>
            <input type="text" name="territory_name" value="{{ $territory->territory_name }}" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
