<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-900 text-white min-h-screen flex flex-col">
    
    <!-- Navbar -->
    <nav class="bg-gray-800 border-b border-gray-700 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-purple-500">
                        {{ config('app.name') }}
                    </a>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="/youtube-video-downloader" class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">YouTube</a>
                    <a href="/tiktok-video-downloader" class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">TikTok</a>
                    <a href="/instagram-video-downloader" class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Instagram</a>
                    <a href="/facebook-video-downloader" class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Facebook</a>
                    <a href="/twitter-video-downloader" class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Twitter</a>
                    <a href="/pinterest-video-downloader" class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Pinterest</a>
                    <a href="/reddit-video-downloader" class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Reddit</a>
                    <a href="/bilibili-video-downloader" class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Bilibili</a>
                    <a href="/spotify-video-downloader" class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Spotify</a>
                    <!-- Static Pages -->
                    <div class="h-6 w-px bg-gray-700 mx-2"></div>
                    <a href="{{ route('about') }}" class="text-gray-300 hover:text-white text-sm font-medium">About</a>
                    <a href="{{ route('contact') }}" class="text-gray-300 hover:text-white text-sm font-medium">Contact</a>
                    @auth
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Admin Panel</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 py-8 border-t border-gray-800 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <div class="mt-4 flex justify-center space-x-6">
                <a href="{{ route('privacy') }}" class="text-gray-600 hover:text-gray-400 text-sm">Privacy Policy</a>
                <a href="{{ route('dmca') }}" class="text-gray-600 hover:text-gray-400 text-sm">DMCA</a>
                <a href="{{ route('terms') }}" class="text-gray-600 hover:text-gray-400 text-sm">Terms of Service</a>
            </div>
            <p class="text-gray-700 text-xs mt-4">We are not affiliated with any social media platforms.</p>
        </div>
    </footer>

</body>
</html>
