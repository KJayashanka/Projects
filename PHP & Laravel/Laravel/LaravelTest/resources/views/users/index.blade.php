@extends('layouts.app')

@section('content')
    <h1>User List</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>NIC</th>
                <th>Address</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Territory</th>
                <th>Username</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->nic }}</td>
                    <td>{{ $user->address }}</td>
                    <td>{{ $user->mobile }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->gender }}</td>
                    <td>{{ $user->territory->territory_name }}</td>
                    <td>{{ $user->username }}</td>
                    <td>
                        <a href="{{ route('user.edit', ['user' => $user->id]) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form method="post" action="{{ route('user.destroy', ['user' => $user->id]) }}" style="display: inline;">
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('user.create') }}" class="btn btn-success">Add User</a>
@endsection
