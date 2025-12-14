@extends('layouts.guest_custom')

@section('content')
<div class="py-16 bg-gray-900 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-white mb-8">Terms of Service</h1>
        
        <div class="prose prose-invert max-w-none text-gray-300">
            <p>Last updated: {{ date('F d, Y') }}</p>

            <h2 class="text-2xl font-bold text-white mt-8 mb-4">1. Acceptance of Terms</h2>
            <p class="mb-4">
                By accessing and using {{ config('app.name') }}, you accept and agree to be bound by the terms and provision of this agreement.
            </p>

            <h2 class="text-2xl font-bold text-white mt-8 mb-4">2. Use License</h2>
            <p class="mb-4">
                Permission is granted to temporarily download the materials (information or software) on {{ config('app.name') }}'s website for personal, non-commercial transitory viewing only.
            </p>
            <p class="mb-4">
                You agree NOT to use the service for:
            </p>
             <ul class="list-disc pl-6 space-y-2 mb-4">
                <li>Copyright infringement or downloading protected content without permission.</li>
                <li>Any commercial purpose, or for any public display.</li>
                <li>Attempting to reverse engineer any software contained on the site.</li>
            </ul>

            <h2 class="text-2xl font-bold text-white mt-8 mb-4">3. Disclaimer</h2>
            <p class="mb-4">
                The materials on {{ config('app.name') }}'s website are provided "as is". {{ config('app.name') }} makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties.
            </p>
        </div>
    </div>
</div>
@endsection
