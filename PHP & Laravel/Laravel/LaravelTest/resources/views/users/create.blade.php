@extends('layouts.app')

@section('content')
    <h1>User Registration</h1>

    <form method="post" action="{{ route('user.store') }}">
        @csrf
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="nic">NIC:</label>
            <input type="text" name="nic" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" name="address" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="mobile">Mobile:</label>
            <input type="text" name="mobile" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="gender">Gender:</label>
            <select name="gender" class="form-control" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="form-group">
            <label for="territory_id">Select Territory:</label>
            <select name="territory_id" class="form-control">
                @foreach ($territories as $territory)
                    <option value="{{ $territory->id }}">{{ $territory->territory_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Save User</button>
    </form>
@endsection
