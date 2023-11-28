@extends('layouts.app')

@section('content')
    <h1>Zone Registration</h1>
    <form action="{{ route('zone.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="zone_code">Zone Code</label>
            <input type="text" name="zone_code" class="form-control" value="{{ $generatedZoneCode }}" readonly>
        </div>
        <div class="form-group">
            <label for="long_description">Long Description</label>
            <input type="text" name="long_description" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="short_description">Short Description</label>
            <input type="text" name="short_description" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Save Zone</button>
    </form>
@endsection
