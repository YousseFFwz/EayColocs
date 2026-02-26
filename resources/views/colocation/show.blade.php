<!DOCTYPE html>
<html>
<head>
    <title>{{ $colocation->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<nav class="bg-white shadow-sm px-8 py-4 flex justify-between items-center">
    <h1 class="text-xl font-bold text-blue-600">EasyColoc</h1>
    <form method="POST" action="/logout">
        @csrf
        <button class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
            Logout
        </button>
    </form>
</nav>

<div class="max-w-6xl mx-auto px-6 py-10">

    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800">
            {{ $colocation->name }}
        </h2>
        <p class="text-gray-500 mt-1">
            {{ $colocation->description }}
        </p>
    </div>

    <!-- ================= MEMBERS ================= -->
    <div class="bg-white p-6 rounded-2xl shadow mb-8">
        <h3 class="text-lg font-semibold mb-4">Members</h3>

        <div class="grid md:grid-cols-2 gap-4">
            @foreach($colocation->users as $user)
                <div class="flex justify-between items-center bg-gray-50 p-4 rounded-xl">
                    <div>
                        <p class="font-medium">{{ $user->name }}</p>
                        <span class="text-sm text-gray-500">
                            {{ ucfirst($user->pivot->role) }}
                        </span>
                    </div>

                    @if($pivot->role === 'owner' && $user->pivot->role === 'member')
                        <form method="POST" action="/colocation/{{ $colocation->id }}/remove/{{ $user->id }}">
                            @csrf
                            <button class="text-red-600 text-sm">
                                Remove
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- ================= ADD CATEGORY ================= -->
    @if($pivot->role === 'owner')
    <div class="bg-white p-6 rounded-2xl shadow mb-8">
        <h3 class="text-lg font-semibold mb-4">Add Category</h3>

        <form method="POST" action="/colocation/{{ $colocation->id }}/category"
              class="flex gap-4">
            @csrf

            <input type="text" name="name"
                   placeholder="Category name"
                   class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">

            <button class="bg-blue-600 text-white px-6 py-2 rounded-lg">
                Add
            </button>
        </form>
        @foreach($colocation->categories as $category)
    <div class="flex justify-between bg-gray-50 p-3 rounded-lg">
        <span>{{ $category->name }}</span>

        @if($pivot->role === 'owner')
            <form method="POST" action="/category/{{ $category->id }}">
                @csrf
                @method('DELETE')
                <button class="text-red-600 text-sm">Delete</button>
            </form>
        @endif
    </div>
@endforeach
    </div>
    @endif

    <!-- ================= ADD EXPENSE ================= -->
    <div class="bg-white p-6 rounded-2xl shadow mb-8">
        <h3 class="text-lg font-semibold mb-4">Add Expense</h3>

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

            <button class="bg-green-600 text-white px-4 py-2 rounded-lg">
                Add
            </button>
        </form>
    </div>

    <!-- ================= EXPENSES ================= -->
    <div class="bg-white p-6 rounded-2xl shadow mb-8">
        <h3 class="text-lg font-semibold mb-4">Expenses</h3>

        @foreach($colocation->expenses as $expense)
            <div class="flex justify-between items-center border-b py-3">
                <div>
                    <p class="font-medium">
                        {{ $expense->description ?? 'No description' }}
                    </p>
                    <p class="text-sm text-gray-500">
                        {{ $expense->user->name }} • {{ $expense->category->name }}
                    </p>
                </div>

                <div class="font-bold">
                    {{ number_format($expense->amount, 2) }} DH
                </div>
            </div>
        @endforeach
    </div>

    <!-- ================= BALANCES ================= -->
    <div class="bg-white p-6 rounded-2xl shadow mb-8">
        <h3 class="text-lg font-semibold mb-4">Balances</h3>

        <p class="mb-4 font-medium">
            Total: {{ number_format($total, 2) }} DH
        </p>

        @foreach($balances as $balance)
            <div class="flex justify-between py-2 border-b">
                <span>{{ $balance['name'] }}</span>

                @if($balance['balance'] > 0)
                    <span class="text-green-600 font-semibold">
                        + {{ number_format($balance['balance'], 2) }} DH
                    </span>
                @elseif($balance['balance'] < 0)
                    <span class="text-red-600 font-semibold">
                        {{ number_format($balance['balance'], 2) }} DH
                    </span>
                @else
                    <span class="text-gray-600">0 DH</span>
                @endif
            </div>
        @endforeach
    </div>

    <!-- ================= WHO PAYS WHO ================= -->
    <div class="bg-white p-6 rounded-2xl shadow">
        <h3 class="text-lg font-semibold mb-4">Who Pays Who</h3>

        @if(count($transactions) > 0)
            @foreach($transactions as $t)
                <div class="flex justify-between items-center border-b py-3">
                    <span>
                        <strong>{{ $t['from'] }}</strong>
                        pays
                        <strong>{{ $t['to'] }}</strong>
                    </span>

                    <span class="text-blue-600 font-semibold">
                        {{ number_format($t['amount'], 2) }} DH
                    </span>
                </div>
            @endforeach
        @else
            <p class="text-gray-500">All settled 🎉</p>
        @endif
    </div>

</div>

</body>
</html>