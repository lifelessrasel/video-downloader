<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>{{ $seo['title'] ?? config('app.name') }}</title>
    <meta name="description" content="{{ $seo['description'] ?? 'Download videos from YouTube, TikTok, Instagram, Facebook, and more for free.' }}">
    <meta name="keywords" content="{{ $seo['keywords'] ?? 'video downloader, mp4 downloader' }}">
    <meta property="og:title" content="{{ $seo['title'] ?? config('app.name') }}">
    <meta property="og:description" content="{{ $seo['description'] ?? '' }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="robots" content="index, follow">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-900 text-white min-h-screen flex flex-col" x-data="{ mobileMenuOpen: false }">
    
    <!-- Navbar -->
    <nav class="bg-gray-800 border-b border-gray-700 relative z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-8">
                    <a href="/" class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-purple-500">
                        {{ config('app.name') }}
                    </a>

                    <!-- Desktop Nav -->
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="/youtube-video-downloader" class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->is('youtube-video-downloader') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">YouTube</a>
                        <a href="/tiktok-video-downloader" class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->is('tiktok-video-downloader') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">TikTok</a>
                        <a href="/instagram-video-downloader" class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->is('instagram-video-downloader') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Instagram</a>
                        <a href="/facebook-video-downloader" class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->is('facebook-video-downloader') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Facebook</a>
                        <a href="/twitter-video-downloader" class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->is('twitter-video-downloader') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Twitter</a>
                        <a href="/pinterest-video-downloader" class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->is('pinterest-video-downloader') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Pinterest</a>
                        <a href="/reddit-video-downloader" class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->is('reddit-video-downloader') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Reddit</a>
                        <a href="/bilibili-video-downloader" class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->is('bilibili-video-downloader') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Bilibili</a>
                        <a href="/spotify-video-downloader" class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->is('spotify-video-downloader') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">Spotify</a>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="hidden md:flex items-center">
                        @auth
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Admin Panel</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Login</a>
                        @endauth
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="flex md:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-300 hover:text-white p-2 focus:outline-none">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                 <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                             </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-collapse x-cloak class="md:hidden bg-gray-800 border-b border-gray-700">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                 <a href="/youtube-video-downloader" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">YouTube</a>
                 <a href="/tiktok-video-downloader" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">TikTok</a>
                 <a href="/instagram-video-downloader" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">Instagram</a>
                 <a href="/facebook-video-downloader" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">Facebook</a>
                 <a href="/twitter-video-downloader" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">Twitter</a>
                 <a href="/pinterest-video-downloader" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">Pinterest</a>
                 <a href="/reddit-video-downloader" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">Reddit</a>
                 <a href="/bilibili-video-downloader" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">Bilibili</a>
                 <a href="/spotify-video-downloader" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">Spotify</a>
                 <div class="border-t border-gray-700 my-2 pt-2">
                    @auth
                        @if(auth()->user()->is_admin)
                             <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">Admin Panel</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">Login</a>
                    @endauth
                 </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center p-6" x-data="videoDownloader()">
        <div class="w-full max-w-4xl space-y-12">
            
            <!-- Hero -->
            <div class="text-center space-y-4">
                <h1 class="text-5xl font-extrabold tracking-tight">
                    @if(isset($seo['name']) && $seo['name'] !== 'Social Media')
                         Download <span class="text-blue-500">{{ $seo['name'] }}</span> Video
                    @else
                        Download Video from <span class="text-blue-500">Anywhere</span>
                    @endif
                </h1>
                <p class="text-xl text-gray-400 max-w-2xl mx-auto">
                    {{ $seo['description'] ?? 'YouTube, TikTok, Instagram, Facebook, Twitter. One tool, free forever.' }}
                </p>
            </div>

            <!-- Input Area (Flexbox to ensure clickability) -->
            <div class="bg-gray-800 p-2 rounded-2xl shadow-2xl border border-gray-700 max-w-3xl mx-auto">
                <div class="flex flex-col sm:flex-row gap-2">
                    <input 
                        type="text" 
                        x-model="url" 
                        @keydown.enter="fetchInfo"
                        placeholder="Paste video URL here..." 
                        class="flex-grow bg-gray-900 text-white placeholder-gray-500 border-none rounded-xl px-6 py-4 text-lg focus:ring-2 focus:ring-blue-500 outline-none"
                    >
                    <button 
                        @click="fetchInfo" 
                        :disabled="loading || !url"
                        class="flex-shrink-0 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold rounded-xl px-8 py-4 text-lg transition-transform active:scale-95 flex items-center justify-center min-w-[120px]"
                    >
                        <span x-show="!loading" class="flex items-center gap-2">
                           <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                           Download
                        </span>
                        <svg x-cloak x-show="loading" class="animate-spin h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </div>
             
             <!-- hCaptcha Placeholder -->
            <div class="flex justify-center">
                 <div class="bg-gray-800/50 border border-gray-700/50 px-6 py-3 rounded-lg text-sm text-gray-500 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    Protected by hCaptcha
                </div>
            </div>

            <!-- Error placeholder moved to status box -->

             <!-- Video Preview & Options -->
            <div x-show="videoData" x-transition.opacity class="grid md:grid-cols-2 gap-8 bg-gray-800 p-6 rounded-2xl shadow-xl border border-gray-700" x-cloak>
                 <!-- Thumbnail -->
                <div class="relative group rounded-xl overflow-hidden aspect-video">
                    <img :src="videoData?.thumbnail" class="w-full h-full object-cover" alt="Video thumbnail">
                    <div class="absolute inset-0 bg-black/40"></div>
                     <div class="absolute bottom-3 left-3 bg-black/70 px-2 py-1 rounded text-xs font-mono" x-text="videoData?.duration ? formatDuration(videoData.duration) : ''"></div>
                </div>

                <!-- Info -->
                <div class="space-y-6">
                    <div>
                        <span class="inline-block px-3 py-1 bg-blue-900/50 text-blue-300 text-xs font-bold rounded-full uppercase mb-2" x-text="videoData?.platform"></span>
                        <h3 class="text-xl font-bold line-clamp-2 leading-tight" x-text="videoData?.title"></h3>
                        
                        <!-- Trim Options -->
                        <div class="mt-4 border-t border-gray-700 pt-4" x-data="{ enableTrim: false }">
                            <div class="flex items-center gap-2 mb-2">
                                <input type="checkbox" id="enableTrim" x-model="enableTrim" class="rounded bg-gray-700 border-gray-600 text-blue-600 focus:ring-blue-500">
                                <label for="enableTrim" class="text-sm font-medium text-gray-300">Cut / Trim Video</label>
                            </div>
                            
                            <div x-show="enableTrim" x-collapse class="grid grid-cols-2 gap-4 mt-2">
                                <div>
                                    <label class="text-xs text-gray-500">Start Time (e.g. 00:00:10)</label>
                                    <input type="text" x-model="$parent.startTime" placeholder="00:00:00" class="w-full bg-gray-900 border border-gray-600 rounded p-2 text-sm text-white focus:border-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">End Time (e.g. 00:00:30)</label>
                                    <input type="text" x-model="$parent.endTime" placeholder="00:00:00" class="w-full bg-gray-900 border border-gray-600 rounded p-2 text-sm text-white focus:border-blue-500 outline-none">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2 max-h-[200px] overflow-y-auto pr-2 custom-scrollbar">
                         <template x-for="format in videoData?.formats" :key="format.format_id">
                             <button @click="startDownload(format.format_id)" class="w-full bg-gray-700 hover:bg-gray-600 p-3 rounded-lg flex items-center justify-between group transition-colors">
                                 <div class="flex items-center gap-3">
                                     <div class="bg-gray-800 p-2 rounded-md"><svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg></div>
                                     <div class="text-left">
                                         <div class="font-bold text-sm" x-text="format.resolution"></div>
                                         <div class="text-xs text-gray-400" x-text="format.ext"></div>
                                     </div>
                                 </div>
                                 <span class="text-xs font-mono text-gray-400 group-hover:text-white" x-text="format.filesize ? formatBytes(format.filesize) : 'N/A'"></span>
                             </button>
                         </template>
                         <button 
                            @click="startDownload('best')"
                            x-show="!videoData?.formats || videoData.formats.length === 0"
                            class="w-full py-4 bg-white text-black font-bold rounded-xl hover:opacity-90 transition-opacity"
                        >
                            Download Best Quality
                        </button>
                    </div>
                </div>
            </div>

            <!-- Processing Status -->
             <div x-show="downloadJobId || error" class="max-w-md mx-auto text-center space-y-4" x-cloak>
                  <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700">
                      <h3 class="font-bold text-lg mb-2" x-text="downloadStatus === 'downloading' ? 'Converting Video...' : (downloadStatus === 'completed' ? 'Ready!' : (downloadStatus === 'failed' ? 'Download Failed' : 'Status'))"></h3>
                      
                      <div x-show="downloadStatus === 'downloading'">
                          <div class="w-full bg-gray-700 rounded-full h-2 mb-4 overflow-hidden">
                              <div class="bg-blue-500 h-2 rounded-full animate-progress-indeterminate"></div>
                          </div>
                          <p class="text-sm text-gray-400 animate-pulse">Please wait, this may take a moment...</p>
                      </div>

                      <div x-show="downloadStatus === 'completed'" class="animate-bounce-in">
                          <a :href="downloadUrl" download class="block w-full py-3 bg-green-600 hover:bg-green-500 text-white font-bold rounded-xl transition-colors">
                              Download File Now
                          </a>
                          <button @click="reset" class="mt-4 text-sm text-gray-400 hover:text-white underline">Convert Another</button>
                      </div>

                      <div x-show="error || downloadStatus === 'failed'" class="text-red-400 font-medium bg-red-900/20 py-2 rounded-lg border border-red-900/50">
                          <p x-text="error || 'An unknown error occurred'"></p>
                          <button @click="reset" class="mt-2 text-xs text-red-300 hover:text-white underline">Try Again</button>
                      </div>
                  </div>
             </div>

        </div>
    </main>

    <!-- Supported Platforms & Features -->
    <section class="py-16 bg-gray-900 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-white mb-4">Why use our Downloader?</h2>
                <p class="text-gray-400">The fastest, safest, and most reliable tool on the web.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 hover:border-blue-500 transition-colors">
                    <div class="w-12 h-12 bg-blue-900/50 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Lightning Fast</h3>
                    <p class="text-gray-400">Powered by high-speed servers and optimized algorithms to convert and download your videos in seconds.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 hover:border-green-500 transition-colors">
                    <div class="w-12 h-12 bg-green-900/50 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">100% Secure & Private</h3>
                    <p class="text-gray-400">We don't store your files. All downloads are temporary and deleted automatically, ensuring your privacy.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 hover:border-purple-500 transition-colors">
                    <div class="w-12 h-12 bg-purple-900/50 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Multi-Platform</h3>
                    <p class="text-gray-400">Supports YouTube, TikTok (No Watermark), Instagram, Facebook, Twitter/X, and many more.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16 bg-gray-800">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-white mb-12">Frequently Asked Questions</h2>
            
            <div class="space-y-4" x-data="{ active: null }">
                <!-- FAQ 1 -->
                <div class="border border-gray-700 rounded-lg bg-gray-900 overflow-hidden">
                    <button @click="active = (active === 1 ? null : 1)" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-800 transition-colors">
                        <span class="font-bold text-lg">Is this service free?</span>
                        <svg class="w-5 h-5 transition-transform" :class="{'rotate-180': active === 1}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="active === 1" x-collapse class="px-6 py-4 text-gray-400 border-t border-gray-700">
                        Yes, our video downloader is and always will be 100% free to use. There are no limits on the number of downloads.
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="border border-gray-700 rounded-lg bg-gray-900 overflow-hidden">
                    <button @click="active = (active === 2 ? null : 2)" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-800 transition-colors">
                        <span class="font-bold text-lg">Does it remove watermarks?</span>
                        <svg class="w-5 h-5 transition-transform" :class="{'rotate-180': active === 2}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="active === 2" x-collapse class="px-6 py-4 text-gray-400 border-t border-gray-700">
                        Yes! For TikTok and other platforms, we automatically attempt to fetch the high-quality, watermark-free version of the video.
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="border border-gray-700 rounded-lg bg-gray-900 overflow-hidden">
                    <button @click="active = (active === 3 ? null : 3)" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-800 transition-colors">
                        <span class="font-bold text-lg">Is it safe to use?</span>
                        <svg class="w-5 h-5 transition-transform" :class="{'rotate-180': active === 3}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="active === 3" x-collapse class="px-6 py-4 text-gray-400 border-t border-gray-700">
                        Absolutely. We do not store your download history or the files you download. Files are temporarily processed for conversion and immediately deleted after you download them.
                    </div>
                </div>
                
                 <!-- FAQ 4 -->
                <div class="border border-gray-700 rounded-lg bg-gray-900 overflow-hidden">
                    <button @click="active = (active === 4 ? null : 4)" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-800 transition-colors">
                        <span class="font-bold text-lg">What platforms are supported?</span>
                        <svg class="w-5 h-5 transition-transform" :class="{'rotate-180': active === 4}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="active === 4" x-collapse class="px-6 py-4 text-gray-400 border-t border-gray-700">
                        We support 1000+ websites including YouTube, Facebook, Instagram, TikTok, Twitter/X, Vimeo, and many more.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 py-8 border-t border-gray-800 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <div class="mt-4 flex justify-center space-x-6">
                <a href="{{ route('privacy') }}" class="text-gray-600 hover:text-gray-400 text-sm">Privacy Policy</a>
                <a href="{{ route('dmca') }}" class="text-gray-600 hover:text-gray-400 text-sm">DMCA</a>
                <a href="{{ route('terms') }}" class="text-gray-600 hover:text-gray-400 text-sm">Terms of Service</a>
                <a href="{{ route('contact') }}" class="text-gray-600 hover:text-gray-400 text-sm">Contact</a>
            </div>
            <p class="text-gray-700 text-xs mt-4">We are not affiliated with any social media platforms. Privacy & GDPR: We use temporary storage. Files deleted after 24h.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('videoDownloader', () => ({
                url: '',
                loading: false,
                error: null,
                videoData: null,
                downloadJobId: null,
                downloadStatus: null,
                downloadUrl: null,
                statusInterval: null,
                startTime: '',
                endTime: '',

                async fetchInfo() {
                    if (!this.url) return;
                    this.loading = true;
                    this.error = null;
                    this.videoData = null;
                    this.resetDownloadState();

                    try {
                        const response = await fetch('{{ route('download.preview') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ url: this.url })
                        });

                        const data = await response.json();
                        
                        if (!response.ok) throw new Error(data.message || 'Failed to fetch video info');
                        
                        this.videoData = data.data;
                    } catch (e) {
                        this.error = e.message;
                    } finally {
                        this.loading = false;
                    }
                },

                async startDownload(formatId) {
                    this.downloadJobId = null;
                    this.downloadStatus = 'downloading'; 
                    this.error = null;
                    
                    try {
                        const response = await fetch('{{ route('download.start') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ 
                                url: this.url, 
                                format: formatId,
                                start_time: this.startTime,
                                end_time: this.endTime
                            })
                        });

                        const data = await response.json();
                        
                        if (!response.ok) throw new Error(data.message || 'Failed to start download');

                        this.downloadJobId = data.job_id;
                        this.pollStatus();

                    } catch (e) {
                        this.error = e.message;
                        this.downloadStatus = 'failed';
                    }
                },

                pollStatus() {
                    if (this.statusInterval) clearInterval(this.statusInterval);
                    this.statusInterval = setInterval(async () => {
                         try {
                            const response = await fetch(`/download/status/${this.downloadJobId}`);
                            
                            if (response.status === 404) {
                                // Job might not be ready in cache yet (worker delay), wait a bit
                                // Don't error out immediately unless it persists
                                return; 
                            }

                            const data = await response.json();

                            if (data.status) {
                               this.downloadStatus = data.status;
                            }

                            if (data.status === 'completed') {
                                this.downloadUrl = data.file_url;
                                clearInterval(this.statusInterval);
                            }
                            
                            if (data.status === 'failed') {
                                this.error = data.error || 'Download failed generically';
                                this.downloadStatus = 'failed';
                                clearInterval(this.statusInterval);
                            }

                         } catch (e) {
                             console.error('Polling error', e); 
                         }
                    }, 2000);
                },

                resetDownloadState() {
                    this.downloadJobId = null;
                    this.downloadStatus = null;
                    this.downloadUrl = null;
                    if (this.statusInterval) clearInterval(this.statusInterval);
                },

                reset() {
                    this.url = '';
                    this.videoData = null;
                    this.startTime = '';
                    this.endTime = '';
                    this.resetDownloadState();
                },

                formatDuration(seconds) {
                    return new Date(seconds * 1000).toISOString().substr(11, 8).replace(/^00:/, '');
                },

                formatBytes(bytes, decimals = 2) {
                    if (!+bytes) return '0 Bytes';
                    const k = 1024;
                    const dm = decimals < 0 ? 0 : decimals;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`;
                }
            }));
        });
    </script>
    <style>
        .animate-progress-indeterminate {
            animation: progress-indeterminate 1.5s infinite linear;
            transform-origin: 0% 50%;
        }
        @keyframes progress-indeterminate {
            0% { transform: translateX(0) scaleX(0.2); }
            40% { transform: translateX(30%) scaleX(0.5); }
            100% { transform: translateX(100%) scaleX(0.2); }
        }
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #4B5563; border-radius: 4px; }
    </style>
</body>
</html>
