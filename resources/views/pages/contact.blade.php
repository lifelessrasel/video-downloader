@extends('layouts.guest_custom')

@section('content')
<div class="py-16 bg-gray-900 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-white mb-8">Contact Us</h1>
        
        <div class="grid md:grid-cols-2 gap-12">
            <div>
                <p class="text-xl text-gray-300 mb-8">
                    We'd love to hear from you. Whether you have a question about features, trials, pricing, or need a demo, our team is ready to answer all your questions.
                </p>
                
                <div class="space-y-6">
                    <div class="flex items-start">
                        <svg class="h-6 w-6 text-blue-500 mt-1 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <div>
                            <h3 class="text-lg font-medium text-white">Email</h3>
                            <p class="text-gray-400">support@example.com</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700">
                <form action="#" method="POST" class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-400 mb-2">Name</label>
                        <input type="text" id="name" class="w-full bg-gray-900 border border-gray-600 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Your Name">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-400 mb-2">Email</label>
                        <input type="email" id="email" class="w-full bg-gray-900 border border-gray-600 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 outline-none" placeholder="you@example.com">
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-400 mb-2">Message</label>
                        <textarea id="message" rows="4" class="w-full bg-gray-900 border border-gray-600 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 outline-none" placeholder="How can we help?"></textarea>
                    </div>
                    <button type="button" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition-transform active:scale-95">
                        Send Message
                    </button>
                    <!-- Note: This form is purely frontend for now unless user asks for backend mailing -->
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
