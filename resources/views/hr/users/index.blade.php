@extends('layouts.app')

@section('title', 'Manage Staff - Winbreaker HR')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Manage Staff & Uploaders</h1>
    <a href="{{ route('hr.users.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
        Create Staff Account
    </a>
</div>

@if($users->count() > 0)
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $user->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($user->role == 'staff') bg-blue-100 text-blue-800
                                @else bg-purple-100 text-purple-800
                                @endif">
                                {{ $user->role === 'uploader' ? 'Product Manager' : ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->is_active)
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('hr.users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                            <form method="POST" action="{{ route('hr.users.destroy', $user->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this account?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-6">
        {{ $users->links() }}
    </div>
@else
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <p class="text-gray-600 text-lg mb-4">No staff accounts found.</p>
        <a href="{{ route('hr.users.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 inline-block">
            Create Your First Staff Account
        </a>
    </div>
@endif
@endsection


