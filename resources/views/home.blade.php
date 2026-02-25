<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EasyColoc</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-indigo-600">
                EasyColoc
            </h1>

            <div class="space-x-4">
                <a href="/login"
                   class="text-gray-600 hover:text-indigo-600 font-medium">
                    Login
                </a>

                <a href="/register"
                   class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                    Get Started
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-6 py-20 text-center">
        <h2 class="text-4xl md:text-5xl font-bold text-gray-800 leading-tight">
            Manage Your Shared Expenses <br>
            Without Headaches
        </h2>

        <p class="mt-6 text-gray-600 text-lg max-w-2xl mx-auto">
            EasyColoc helps roommates track expenses, calculate balances,
            and see clearly who owes who — automatically.
        </p>

        <div class="mt-10">
            <a href="/register"
               class="bg-indigo-600 text-white px-8 py-3 rounded-xl text-lg hover:bg-indigo-700 shadow-md">
                Create Your Colocation
            </a>
        </div>
    </section>

    <!-- Features -->
    <section class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-8">

            <div class="p-6 rounded-2xl shadow-sm border">
                <h3 class="text-xl font-semibold text-gray-800 mb-3">
                    Add Expenses Easily
                </h3>
                <p class="text-gray-600">
                    Track shared costs like rent, groceries, and utilities in seconds.
                </p>
            </div>

            <div class="p-6 rounded-2xl shadow-sm border">
                <h3 class="text-xl font-semibold text-gray-800 mb-3">
                    Automatic Balance
                </h3>
                <p class="text-gray-600">
                    Instantly know who owes who without manual calculations.
                </p>
            </div>

            <div class="p-6 rounded-2xl shadow-sm border">
                <h3 class="text-xl font-semibold text-gray-800 mb-3">
                    Secure & Fair
                </h3>
                <p class="text-gray-600">
                    Transparent system with roles, permissions, and reputation tracking.
                </p>
            </div>

        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-100 py-6 text-center text-gray-500 text-sm">
        © {{ date('Y') }} EasyColoc. All rights reserved.
    </footer>

</body>
</html>