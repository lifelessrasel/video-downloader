@extends('layouts.guest_custom')

@section('content')
<div class="py-16 bg-gray-900 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-white mb-8">Privacy Policy</h1>
        
        <div class="prose prose-invert max-w-none text-gray-300">
            <p>Last updated: {{ date('F d, Y') }}</p>

            <p class="mb-4">
                This Privacy Policy describes Our policies and procedures on the collection, use and disclosure of Your information when You use the Service and tells You about Your privacy rights and how the law protects You.
            </p>

            <h2 class="text-2xl font-bold text-white mt-8 mb-4">Collecting and Using Your Personal Data</h2>
            
            <h3 class="text-xl font-bold text-white mt-4 mb-2">Types of Data Collected</h3>
            
            <h4 class="text-lg font-semibold text-white mt-2">Usage Data</h4>
            <p class="mb-4">
                Usage Data is collected automatically when using the Service. usage Data may include information such as Your Device's Internet Protocol address (e.g. IP address), browser type, browser version, the pages of our Service that You visit, the time and date of Your visit, and other diagnostic data.
            </p>

            <h2 class="text-2xl font-bold text-white mt-8 mb-4">Data Retention</h2>
            <p class="mb-4">
                <strong>We do not retain downloaded files.</strong> All files processed by our Service are deleted automatically after a short period (typically 24 hours or less) to ensure server performance and user privacy. We do not inspect the contents of files you download.
            </p>

            <h2 class="text-2xl font-bold text-white mt-8 mb-4">Cookies</h2>
            <p class="mb-4">
                We use Cookies and similar tracking technologies to track the activity on Our Service and store certain information. You can instruct your browser to refuse all Cookies or to indicate when a Cookie is being sent.
            </p>
        </div>
    </div>
</div>
@endsection
