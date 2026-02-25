<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
        <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Create Account</h2>

        <form method="POST" action="/register" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name"
                       class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none"
                       placeholder="Enter your name">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email"
                       class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none"
                       placeholder="Enter your email">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password"
                       class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none"
                       placeholder="Create a password">
            </div>

            <button type="submit"
                    class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition">
                Register
            </button>
        </form>

        <p class="text-sm text-center mt-4">
            Already have an account?
            <a href="/login" class="text-green-600 hover:underline">Login</a>
        </p>
    </div>

</body>
</html>