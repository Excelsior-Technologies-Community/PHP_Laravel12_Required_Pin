<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Vite for Tailwind CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen">
        {{-- 🔝 NAVBAR --}}
        <nav class="bg-white border-b border-gray-100 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="text-xl font-bold text-blue-600">
                            {{ config('app.name', 'Farmer Life') }}
                        </a>
                    </div>

                    <div class="flex items-center gap-4">
                        @auth
                            <span class="text-gray-600 text-sm font-medium">{{ Auth::user()->name }}</span>
                            
                            <a href="{{ route('changePinView') }}" class="text-sm text-blue-500 hover:text-blue-700 font-semibold">
                                Change PIN
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="text-sm text-red-500 hover:text-red-700 font-semibold">
                                    Logout
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        {{-- 🔽 CONTENT --}}
        <main class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>