@extends('layouts.app')

@section('content')
    <h1>Edit Zone</h1>
    <form action="{{ route('zone.update', $zone->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="long_description">Long Description</label>
            <input type="text" name="long_description" class="form-control" value="{{ $zone->long_description }}" required>
        </div>
        <div class="form-group">
            <label for="short_description">Short Description</label>
            <input type="text" name="short_description" class="form-control" value="{{ $zone->short_description }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
