<!DOCTYPE html>
<html>
    <head>
        <title>{{ $colocation->name }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
  <body class="bg-gray-100 min-h-screen">

<nav class="bg-white shadow-sm px-8 py-4 flex justify-between items-center">
    <div class="flex items-center gap-4">
        <a href="/dashboard"
           class="text-gray-600 hover:text-blue-600 text-sm font-medium">
            ← Dashboard
        </a>
        <h1 class="text-xl font-bold text-blue-600">EasyColoc</h1>
    </div>

    <form method="POST" action="/logout">
        @csrf
        <button class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
            Logout
        </button>
    </form>
</nav>

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="max-w-7xl mx-auto px-6 py-10">

    <!-- Header Card -->
    <div class="bg-white rounded-2xl shadow p-8 mb-8">
        <h2 class="text-3xl font-bold text-gray-800">
            {{ $colocation->name }}
        </h2>
        <p class="text-gray-500 mt-2">
            {{ $colocation->description }}
        </p>
    </div>

    <!-- Members -->
    <div class="bg-white rounded-2xl shadow p-6 mb-8">
        <h3 class="text-xl font-semibold mb-6">Members</h3>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($colocation->users as $user)
                <div class="bg-gray-50 p-4 rounded-xl border flex justify-between items-center">

                    <div>
                        <p class="font-medium text-gray-800">
                            {{ $user->name }}
                        </p>
                        <span class="text-xs text-gray-500 uppercase tracking-wide">
                            {{ $user->pivot->role }}
                        </span>
                    </div>

                    @if($pivot->role === 'owner' && $user->pivot->role === 'member')
                        <form method="POST" action="/colocation/{{ $colocation->id }}/remove/{{ $user->id }}">
                            @csrf
                            <button class="text-red-500 text-sm hover:underline">
                                Remove
                            </button>
                        </form>
                    @endif

                </div>
            @endforeach
        </div>
    </div>

    <!-- Categories -->
    <div class="bg-white rounded-2xl shadow p-6 mb-8">
        <h3 class="text-xl font-semibold mb-6">Categories</h3>

        <div class="flex flex-wrap gap-3 mb-6">
            @foreach($colocation->categories as $category)
                <div class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full flex items-center gap-3">

                    <span>{{ $category->name }}</span>

                    @if($pivot->role === 'owner')
                        <form method="POST" action="/category/{{ $category->id }}">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-500 text-xs hover:underline">
                                ×
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>

        @if($pivot->role === 'owner')
        <form method="POST" action="/colocation/{{ $colocation->id }}/category"
              class="flex gap-4">
            @csrf

            <input type="text" name="name"
                   placeholder="New category..."
                   class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">

            <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                Add
            </button>
        </form>
        @endif
    </div>

    <!-- Add Expense -->
    <div class="bg-white rounded-2xl shadow p-6 mb-8">
        <h3 class="text-xl font-semibold mb-6">Add Expense</h3>

        <form method="POST" action="/colocation/{{ $colocation->id }}/expense"
              class="grid md:grid-cols-4 gap-4">
            @csrf

            <input type="number" step="0.01" name="amount"
                   placeholder="Amount"
                   class="px-4 py-2 border rounded-lg">

            <select name="category_id"
                    class="px-4 py-2 border rounded-lg">
                @foreach($colocation->categories as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <input type="text" name="description"
                   placeholder="Description"
                   class="px-4 py-2 border rounded-lg">

            <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                Add
            </button>
        </form>
    </div>

    <!-- Expenses -->
    <div class="bg-white rounded-2xl shadow p-6 mb-8">
        <h3 class="text-xl font-semibold mb-6">Expenses</h3>

        @foreach($colocation->expenses as $expense)
            <div class="flex justify-between items-center border-b py-4">
                <div>
                    <p class="font-medium">
                        {{ $expense->description ?? 'No description' }}
                    </p>
                    <p class="text-sm text-gray-500">
                        {{ $expense->user->name }} • {{ $expense->category->name }}
                    </p>
                </div>

                <div class="text-lg font-bold text-gray-800">
                    {{ number_format($expense->amount, 2) }} DH
                </div>
            </div>
        @endforeach
    </div>

    <!-- Balances -->
    <div class="bg-white rounded-2xl shadow p-6 mb-8">
        <h3 class="text-xl font-semibold mb-6">Balances</h3>

        <p class="mb-6 text-gray-700 font-medium">
            Total: {{ number_format($total, 2) }} DH
        </p>

        @foreach($balances as $balance)
            <div class="flex justify-between items-center py-3 border-b">
                <span class="font-medium">{{ $balance['name'] }}</span>

                @if($balance['balance'] > 0)
                    <span class="text-green-600 font-semibold">
                        + {{ number_format($balance['balance'], 2) }} DH
                    </span>
                @elseif($balance['balance'] < 0)
                    <span class="text-red-600 font-semibold">
                        {{ number_format($balance['balance'], 2) }} DH
                    </span>
                @else
                    <span class="text-gray-500">0 DH</span>
                @endif
            </div>
        @endforeach
    </div>

  @if($pivot->role === 'owner')
<form method="POST" action="/colocation/{{ $colocation->id }}/invite">
    @csrf
    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">
        Generate Invite Link
    </button>
</form>
@endif

    <!-- Danger Zone -->
    <div class="bg-white rounded-2xl shadow p-6 border border-red-200">
        <h3 class="text-lg font-semibold text-red-600 mb-4">
            Danger Zone
        </h3>

        <form method="POST" action="/colocation/{{ $colocation->id }}/quit">
            @csrf

            @if($pivot->role === 'owner')
                <button class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition">
                    Cancel Colocation
                </button>
            @else
                <button class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-600 transition">
                    Quit Colocation
                </button>
            @endif
        </form>
    </div>


</div>

</body>
</html>