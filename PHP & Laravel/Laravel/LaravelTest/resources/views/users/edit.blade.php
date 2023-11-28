@extends('layouts.app')

@section('content')
    <h1>Edit User</h1>

    <form method="post" action="{{ route('user.update', ['user' => $user->id]) }}">
        @csrf
        @method('PATCH')
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="nic">NIC:</label>
            <input type="text" name="nic" value="{{ $user->nic }}" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" name="address" value="{{ $user->address }}" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="mobile">Mobile:</label>
            <input type="text" name="mobile" value="{{ $user->mobile }}" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="gender">Gender:</label>
            <select name="gender" class="form-control" required>
                <option value="Male" {{ $user->gender == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ $user->gender == 'Female' ? 'selected' : '' }}>Female</option>
                <option value="Other" {{ $user->gender == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>
        <div class="form-group">
            <label for="territory_id">Select Territory:</label>
            <select name="territory_id" class="form-control" required>
                @foreach ($territories as $territory)
                    <option value="{{ $territory->id }}" {{ $user->territory_id == $territory->id ? 'selected' : '' }}>
                        {{ $territory->territory_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" value="{{ $user->username }}" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
