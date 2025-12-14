@extends('layouts.guest_custom')

@section('content')
<div class="py-16 bg-gray-900 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-white mb-8">About Us</h1>
        
        <div class="prose prose-invert max-w-none text-gray-300">
            <p class="text-xl mb-6">
                Welcome to {{ config('app.name') }}, the ultimate tool for downloading videos from social media platforms.
            </p>

            <h2 class="text-2xl font-bold text-white mt-8 mb-4">Our Mission</h2>
            <p class="mb-4">
                Our mission is to provide a free, fast, and secure way for content creators, archivists, and everyday users to save offline copies of their favorite videos. We believe in an open internet where content is accessible and preservable.
            </p>

            <h2 class="text-2xl font-bold text-white mt-8 mb-4">How It Works</h2>
            <p class="mb-4">
                We utilize advanced processing algorithms (powered by industry-standard tools like FFmpeg and yt-dlp) to analyze video URLs, extract media streams, and provide them to you in the highest possible quality.
            </p>
            <ul class="list-disc pl-6 space-y-2 mb-4">
                <li><strong>No Registration:</strong> We don't ask for your email or personal info.</li>
                <li><strong>No Logs:</strong> We don't track what you download.</li>
                <li><strong>High Speed:</strong> Our servers are optimized for bandwidth.</li>
            </ul>

            <h2 class="text-2xl font-bold text-white mt-8 mb-4">Contact</h2>
            <p>
                Have questions or suggestions? Reach out to us via our <a href="{{ route('contact') }}" class="text-blue-400 hover:text-blue-300">Contact Page</a>.
            </p>
        </div>
    </div>
</div>
@endsection
