<?php

namespace App\Http\Controllers;

use App\Models\Territory;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        
        $territories = Territory::all();
        return view('users.create', compact('territories'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required',
            'nic' => 'required',
            'address' => 'required',
            'mobile' => 'required',
            'email' => 'required|email',
            'gender' => 'required',
            'territory_id' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required',
        ]);

        User::create($validatedData);

        return redirect()->route('user.index')->with('success', 'User registered successfully!');
    }

    public function edit($id)
    {
        $user = User::find($id);
        $territories = Territory::all();
        return view('users.edit', compact('user', 'territories'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request data here if needed
        $user = User::find($id);
        $user->name = $request->input('name');
        $user->nic = $request->input('nic');
        $user->address = $request->input('address');
        $user->mobile = $request->input('mobile');
        $user->email = $request->input('email');
        $user->gender = $request->input('gender');
        $user->territory_id = $request->input('territory_id');
        $user->username = $request->input('username');
        
        // Check if a new password was provided, and update it if necessary
        if ($request->has('password')) {
            $user->password = bcrypt($request->input('password'));
        }
        
        $user->save();

        return redirect()->route('user.index')->with('success', 'User updated successfully!');
    }
}
