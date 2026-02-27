<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<nav class="bg-white shadow px-8 py-4 flex justify-between items-center">
    <h1 class="text-xl font-bold text-blue-600">EasyColoc Admin</h1>

    <form method="POST" action="/logout">
        @csrf
        <button class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
            Logout
        </button>
    </form>
</nav>

<div class="max-w-7xl mx-auto px-6 py-10">

    {{-- Alerts --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid md:grid-cols-4 gap-6 mb-10">

        <div class="bg-white p-6 rounded-2xl shadow">
            <p class="text-gray-500 text-sm">Total Users</p>
            <h2 class="text-2xl font-bold mt-2">{{ $totalUsers }}</h2>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow">
            <p class="text-gray-500 text-sm">Banned Users</p>
            <h2 class="text-2xl font-bold mt-2 text-red-600">{{ $bannedUsers }}</h2>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow">
            <p class="text-gray-500 text-sm">Total Colocations</p>
            <h2 class="text-2xl font-bold mt-2">{{ $totalColocations }}</h2>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow">
            <p class="text-gray-500 text-sm">Total Expenses</p>
            <h2 class="text-2xl font-bold mt-2 text-green-600">
                {{ number_format($totalExpenses, 2) }} DH
            </h2>
        </div>

    </div>

    <div class="bg-white rounded-2xl shadow p-6 mb-10">

    <h3 class="text-2xl font-semibold mb-6">Your Colocations</h3>

    <div class="space-y-4">

        @foreach($colocations as $colocation)
            <div class="flex justify-between items-center bg-gray-50 p-4 rounded-lg">

                <div>
                    <p class="font-medium text-gray-800">
                        {{ $colocation->name }}
                    </p>
                    <p class="text-sm text-gray-500">
                        {{ $colocation->description }}
                    </p>
                </div>

                <a href="/colocation/{{ $colocation->id }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">
                    Open
                </a>

            </div>
        @endforeach

    </div>

</div>

    {{-- Users Table --}}
    <div class="bg-white rounded-2xl shadow p-6">

        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-semibold">All Users</h3>
            <span class="text-sm text-gray-500">
                {{ $users->count() }} users
            </span>
        </div>
        

        <div class="overflow-x-auto">
            <table class="min-w-full text-left">

                <thead class="border-b">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Name</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Role</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($users as $user)
                        <tr class="border-b hover:bg-gray-50 transition">

                            <td class="py-3 px-4">{{ $user->id }}</td>

                            <td class="py-3 px-4 font-medium">
                                {{ $user->name }}
                            </td>

                            <td class="py-3 px-4 text-gray-600">
                                {{ $user->email }}
                            </td>

                            <td class="py-3 px-4">
                                @if($user->role === 'admin')
                                    <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-sm">
                                        Admin
                                    </span>
                                @else
                                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-sm">
                                        User
                                    </span>
                                @endif
                            </td>

                            <td class="py-3 px-4">
                                @if($user->is_banned)
                                    <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-sm">
                                        Banned
                                    </span>
                                @else
                                    <span class="bg-green-100 text-green-600 px-2 py-1 rounded text-sm">
                                        Active
                                    </span>
                                @endif
                            </td>

                            <td class="py-3 px-4">
                                @if($user->role !== 'admin')

                                    @if($user->is_banned)
                                        <form method="POST" action="/admin/user/{{ $user->id }}/unban">
                                            @csrf
                                            <button class="bg-green-500 text-white px-3 py-1 rounded-lg hover:bg-green-600 text-sm">
                                                Unban
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="/admin/user/{{ $user->id }}/ban">
                                            @csrf
                                            <button class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 text-sm">
                                                Ban
                                            </button>
                                        </form>
                                    @endif

                                @else
                                    <span class="text-gray-400 text-sm">Protected</span>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

    </div>

</div>

</body>
</html>