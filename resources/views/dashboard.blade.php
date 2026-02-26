@php
    use Illuminate\Support\Facades\Auth;
@endphp
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white shadow-md p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-blue-600">EasyColoc</h1>

        <form method="POST" action="/logout">
            @csrf
            <button class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                Logout
            </button>
        </form>
    </nav>

    <div class="max-w-6xl mx-auto mt-10 px-6">

        <h2 class="text-3xl font-bold mb-8">
                 Welcome, {{ Auth::user()->name }} 
        </h2>

        <!-- Top Cards -->
        <div class="grid md:grid-cols-2 gap-6 mb-10">

            <div class="bg-white p-6 rounded-2xl shadow">
                <h3 class="text-xl font-semibold mb-2">Your Status</h3>
                <p class="text-gray-600">
                    You are not in any colocation yet.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow text-center">
                <h3 class="text-xl font-semibold mb-4">Create a Colocation</h3>
                <a href="/colocation/create"
                   class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    Create Now
                </a>
            </div>

        </div>

        <!-- Big Users Card -->
        <div class="bg-white rounded-2xl shadow p-6">

            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-semibold">All Users</h3>
                <span class="text-sm text-gray-500">
                    Total: {{ $users->count() }}
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
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($users as $user)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="py-3 px-4">{{ $user->id }}</td>
                                <td class="py-3 px-4 font-medium">{{ $user->name }}</td>
                                <td class="py-3 px-4 text-gray-600">{{ $user->email }}</td>
                                <td class="py-3 px-4">
                                    @if($user->role === 'admin')
                                        <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-sm">
                                            Admin
                                        </span>
                                    @else
                                        <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded text-sm">
                                            User
                                        </span>
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