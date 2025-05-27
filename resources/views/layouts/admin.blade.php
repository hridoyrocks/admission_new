<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-white w-64 py-4 px-6">
            <h2 class="text-2xl font-semibold mb-8">Admin Panel</h2>
            <nav>
                <a href="{{ route('admin.dashboard') }}" class="block py-2 px-4 rounded hover:bg-gray-700 mb-2 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">Dashboard</a>
                <a href="{{ route('admin.applications') }}" class="block py-2 px-4 rounded hover:bg-gray-700 mb-2 {{ request()->routeIs('admin.applications') ? 'bg-gray-700' : '' }}">Applications</a>
                <a href="{{ route('admin.course.settings') }}" class="block py-2 px-4 rounded hover:bg-gray-700 mb-2 {{ request()->routeIs('admin.course.settings') ? 'bg-gray-700' : '' }}">Course Settings</a>
                <a href="{{ route('admin.batches') }}" class="block py-2 px-4 rounded hover:bg-gray-700 mb-2 {{ request()->routeIs('admin.batches') ? 'bg-gray-700' : '' }}">Batch Management</a>
                <a href="{{ route('admin.payment.methods') }}" class="block py-2 px-4 rounded hover:bg-gray-700 mb-2 {{ request()->routeIs('admin.payment.methods') ? 'bg-gray-700' : '' }}">Payment Methods</a>
                <a href="{{ route('admin.time.conditions') }}" class="block py-2 px-4 rounded hover:bg-gray-700 mb-2 {{ request()->routeIs('admin.time.conditions') ? 'bg-gray-700' : '' }}">Time Conditions</a>
                <form method="POST" action="{{ route('admin.logout') }}" class="mt-8">
                    @csrf
                    <button type="submit" class="w-full text-left py-2 px-4 rounded hover:bg-red-600">Logout</button>
                </form>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <header class="bg-white shadow-sm px-6 py-4">
                <h1 class="text-2xl font-semibold text-gray-800">@yield('header')</h1>
            </header>
            <main class="p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
