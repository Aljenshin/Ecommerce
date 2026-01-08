<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // HR can only see staff accounts
        $users = User::where('role', User::ROLE_STAFF)
            ->orderBy('name')
            ->paginate(15);

        return view('hr.users.index', compact('users'));
    }

    public function create()
    {
        // HR can only create Staff accounts
        $roles = [
            User::ROLE_STAFF => 'Staff',
        ];

        return view('hr.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:'.User::ROLE_STAFF],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_STAFF,
            'is_active' => true,
        ]);

        return redirect()->route('hr.users.index')->with('success', 'Staff account created successfully.');
    }

    public function edit(User $user)
    {
        // HR can only edit staff accounts
        if ($user->role !== User::ROLE_STAFF) {
            abort(403, 'You can only edit staff accounts.');
        }

        $roles = [
            User::ROLE_STAFF => 'Staff',
        ];

        return view('hr.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // HR can only edit staff accounts
        if ($user->role !== User::ROLE_STAFF) {
            abort(403, 'You can only edit staff accounts.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'in:'.User::ROLE_STAFF],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => User::ROLE_STAFF,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('hr.users.index')->with('success', 'Staff account updated successfully.');
    }

    public function destroy(User $user)
    {
        // HR can only delete staff accounts
        if ($user->role !== User::ROLE_STAFF) {
            abort(403, 'You can only delete staff accounts.');
        }

        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('hr.users.index')->with('success', 'Staff account deleted successfully.');
    }
}
