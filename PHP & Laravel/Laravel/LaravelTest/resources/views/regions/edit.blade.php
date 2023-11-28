@extends('layouts.app')

@section('content')
    <h1>Edit Region</h1>
    <form method="post" action="{{ route('region.update', ['id' => $region->id]) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="zone_id">Select Zone:</label>
            <select name="zone_id" class="form-control">
                <option value="">Select Zone</option>
                @foreach ($zones as $zone)
                    <option value="{{ $zone['id'] }}" {{ $zone['id'] == $region->zone_id ? 'selected' : '' }}>
                        {{ $zone['zone_code'] }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="region_code">Region Code:</label>
            <input type="text" name="region_code" value="{{ $region->region_code }}" class="form-control" readonly>
        </div>
        <div class="form-group">
            <label for="region_name">Region Name:</label>
            <input type="text" name="region_name" value="{{ $region->region_name }}" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
