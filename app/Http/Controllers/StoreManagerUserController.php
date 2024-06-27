<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class StoreManagerUserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $users = User::searchUsers($search);
        return view('storemanager.users.index', compact('users', 'search'));
    }
    
    public function detailUser(User $user)
    {
        return view('storemanager.users.detail', compact('user'));
    }

    public function createUser()
    {
        return view('storemanager.users.create');
    }

    public function storeUser(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'usertype' => 'required|string',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        try {
            User::create($validatedData);
            return redirect()->route('storemanager.users.index')->with('success', 'User added successfully');
        } catch (\Exception $e) {
            return redirect()->route('storemanager.users.create')->with('error', 'Failed to add user: ' . $e->getMessage());
        }
    }

    public function editUser(User $user)
    {
        return view('storemanager.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'usertype' => 'required|string',
        ]);

        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($request->password);
        } else {
            unset($validatedData['password']);
        }

        try {
            $user->update($validatedData);
            return redirect()->route('storemanager.users.index')->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            return redirect()->route('storemanager.users.edit', $user->id)->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    public function destroyUser(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('storemanager.users.index')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('storemanager.users.index')->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    public function verifyUser(User $user)
    {
        try {
            $user->update(['email_verified_at' => Carbon::now()]);
            return redirect()->route('storemanager.users.index')->with('success', 'User verified successfully.');
        } catch (\Exception $e) {
            return redirect()->route('storemanager.users.index')->with('error', 'Failed to verify user: ' . $e->getMessage());
        }
    }
}
