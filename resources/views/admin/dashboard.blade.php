@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8 px-4">
    <div class="max-w-2xl mx-auto">
        <!-- Nagłówek -->
        <div class="bg-blue-500 text-white p-6 rounded-lg shadow-lg mb-6">
            <h2 class="text-2xl font-semibold text-center">Admin Dashboard</h2>
        </div>

        <!-- Lista Użytkowników -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
                <h3 class="text-xl font-semibold mb-4">Users List</h3>
                <!-- Tabela Użytkowników -->
                <table class="min-w-full table-auto mb-6">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">ID</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Name</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Email</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Role</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr class="border-b">
                            <td class="py-3 px-4 text-sm">{{ $user->id }}</td>
                            <td class="py-3 px-4 text-sm">{{ $user->name }}</td>
                            <td class="py-3 px-4 text-sm">{{ $user->email }}</td>
                            <td class="py-3 px-4 text-sm">{{ $user->role }}</td>
                            <td class="py-3 px-4 text-sm">
                                <!-- Przyciski Akcji -->
                                @if ($user->role !== 'admin')
                                    <a href="{{ route('admin.makeAdmin', $user->id) }}" class="text-green-500 hover:text-green-700 mr-4">
                                        Make Admin
                                    </a>
                                @endif
                                <a href="{{ route('admin.deleteUser', $user->id) }}" onclick="return confirm('Are you sure you want to delete this user?')" class="text-red-500 hover:text-red-700">
                                    Delete
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Paginacja -->
                <div class="mt-4">
                    {{ $users->links() }} <!-- Paginacja -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
