<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Dashboard | User";
        $users = User::latest()->paginate(10);

        return view('dashboard.user.index', compact('title', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Dashboard | Create User";

        return view('dashboard.user.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return dd($request->all());
        $validatedData = $request->validate([
            "name" => "required|max:255",
            "slug" => "required|unique:users",
            "email" => "required|email:dns|unique:users",
            "role" => "required",
            "password" => "required|min:8"
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        User::create($validatedData);

        return redirect('/dashboard/user')->with('success', 'User created successfully!!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $title = "Dashboard | Edit User";

        return view('dashboard.user.edit', compact('title', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            "name" => "required|max:255",
            "password" => "required|min:8",
            "role" => "required"
        ];

        if ($request->slug != $user->slug) {
            $rules['slug'] = "required|unique:users";
        }

        if ($request->email != $user->email) {
            $rules['email'] = "required|email|unique:users";
        }

        $validatedData = $request->validate($rules);

        User::where('id', $user->id)->update($validatedData);

        return redirect('/dashboard/user')->with('success', 'User updated successfully!!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        User::destroy($user->id);

        return redirect('/dashboard/user')->with('success', "User deleted successfully!!");
    }
}
