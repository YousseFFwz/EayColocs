<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Colocation</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Top Bar -->
    <nav class="bg-white shadow-sm py-4 px-8 flex justify-between items-center">
        <h1 class="text-xl font-bold text-blue-600">EasyColoc</h1>
        <a href="/dashboard" class="text-sm text-gray-600 hover:text-blue-600 transition">
            Dashboard
        </a>
    </nav>

    <!-- Content -->
    <div class="flex items-center justify-center py-16 px-6">

        <div class="bg-white shadow-2xl rounded-3xl p-10 w-full max-w-xl">

            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">
                    Create a New Colocation
                </h2>
                <p class="text-gray-500 mt-2 text-sm">
                    Set up your shared living space and invite roommates.
                </p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-xl mb-6 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="/colocation/store" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Colocation Name
                    </label>
                    <input 
                        type="text" 
                        name="name"
                        value="{{ old('name') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                        placeholder="Example: Apartment Downtown">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea 
                        name="description"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                        placeholder="Short description about the colocation...">{{ old('description') }}</textarea>
                </div>

                <!-- Submit -->
                <button 
                    type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-xl font-semibold hover:bg-blue-700 active:scale-[0.98] transition duration-200 shadow-md">
                    Create Colocation
                </button>
                
                <div class="mt-6 text-center">
                        <a href="/dashboard"
                        class="text-sm text-gray-500 hover:text-blue-600 transition">
                            ← Back to Dashboard
                        </a>
                </div>
            </form>

        </div>

    </div>

</body>
</html>