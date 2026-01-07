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
        // HR can only see staff and uploader accounts
        $users = User::whereIn('role', [User::ROLE_STAFF, User::ROLE_UPLOADER])
            ->orderBy('name')
            ->paginate(15);

        return view('hr.users.index', compact('users'));
    }

    public function create()
    {
        // HR can only create Staff and Uploader accounts
        $roles = [
            User::ROLE_STAFF => 'Staff',
            User::ROLE_UPLOADER => 'Product Manager / Uploader',
        ];

        return view('hr.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:'.User::ROLE_STAFF.','.User::ROLE_UPLOADER],
            'is_active' => ['nullable', 'boolean'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->boolean('is_active', true),
            'is_admin' => false,
        ]);

        return redirect()->route('hr.users.index')->with('success', 'Staff account created successfully.');
    }

    public function edit(User $user)
    {
        // HR can only edit staff and uploader accounts
        if (!in_array($user->role, [User::ROLE_STAFF, User::ROLE_UPLOADER])) {
            abort(403, 'You can only edit staff and uploader accounts.');
        }

        $roles = [
            User::ROLE_STAFF => 'Staff',
            User::ROLE_UPLOADER => 'Product Manager / Uploader',
        ];

        return view('hr.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // HR can only edit staff and uploader accounts
        if (!in_array($user->role, [User::ROLE_STAFF, User::ROLE_UPLOADER])) {
            abort(403, 'You can only edit staff and uploader accounts.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'in:'.User::ROLE_STAFF.','.User::ROLE_UPLOADER],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('hr.users.index')->with('success', 'Staff account updated successfully.');
    }

    public function destroy(User $user)
    {
        // HR can only delete staff and uploader accounts
        if (!in_array($user->role, [User::ROLE_STAFF, User::ROLE_UPLOADER])) {
            abort(403, 'You can only delete staff and uploader accounts.');
        }

        $user->delete();

        return redirect()->route('hr.users.index')->with('success', 'Staff account deleted successfully.');
    }
}
