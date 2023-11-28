@extends('layouts.app')

@section('content')
    <h1>Region Registration</h1>
    <form method="post" action="{{ route('region.store') }}">
        @csrf
        <div class="form-group">
            <label for="zone_id">Select Zone:</label>
            <select name="zone_id" class="form-control">
                <option value="">Select Zone</option>
                @foreach ($zones as $zone)
                    <option value="{{ $zone['id'] }}">{{ $zone['short_description'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="region_code">Region Code:</label>
            <input type="text" name="region_code" value="{{ $region_code }}" class="form-control" readonly>
        </div>
        <div class="form-group">
            <label for="region_name">Region Name:</label>
            <input type="text" name="region_name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Save Region</button>
    </form>
@endsection
