<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="videoDownloader()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Header Section -->
                    <div class="text-center mb-10">
                        <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-500 to-purple-600 mb-2">
                            Universal Video Downloader
                        </h1>
                        <p class="text-gray-500 dark:text-gray-400">
                            Support for YouTube, TikTok, Instagram, Facebook, Twitter, and more.
                        </p>
                    </div>

                    <!-- Input Section -->
                    <div class="max-w-3xl mx-auto">
                        <div class="relative">
                            <input 
                                type="text" 
                                x-model="url" 
                                @keydown.enter="fetchInfo"
                                placeholder="Paste video URL here..." 
                                class="w-full pl-4 pr-16 py-4 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all text-lg shadow-sm"
                            >

                            <button 
                                type="button"
                                @click="fetchInfo" 
                                :disabled="loading || !url"
                                class="absolute right-2 top-2 bottom-2 w-12 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-medium rounded-lg transition-all shadow-md flex items-center justify-center z-50 cursor-pointer"
                                title="Preview Video"
                            >
                                <!-- Search Icon -->
                                <svg x-show="!loading" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                
                                <!-- Loading Spinner -->
                                <svg x-cloak x-show="loading" class="animate-spin h-5 w-5 text-white absolute" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- hCaptcha Placeholder -->
                        <div class="mt-4 flex justify-center">
                            <div class="bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 p-4 rounded text-sm text-gray-500 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                Security Check (hCaptcha)
                            </div>
                        </div>
                        <p x-show="error" x-text="error" class="mt-2 text-red-500 text-sm text-center"></p>
                    </div>

                    <!-- Video Preview & Options -->
                    <div x-show="videoData" x-transition.opacity class="mt-12 max-w-4xl mx-auto grid md:grid-cols-2 gap-8 animate-fade-in-up">
                        <!-- Thumbnail -->
                        <div class="relative group rounded-xl overflow-hidden shadow-2xl ring-1 ring-gray-900/5">
                            <img :src="videoData?.thumbnail" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" alt="Video thumbnail">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            <div class="absolute bottom-4 left-4 right-4">
                                <span class="bg-black/50 backdrop-blur-md text-white text-xs px-2 py-1 rounded-md" x-text="videoData?.duration ? formatDuration(videoData.duration) : 'Video'"></span>
                            </div>
                        </div>

                        <!-- Details & Download Actions -->
                        <div class="flex flex-col justify-between py-2">
                            <div>
                                <h3 class="text-2xl font-bold mb-2 line-clamp-2" x-text="videoData?.title"></h3>
                                <div class="flex items-center space-x-2 mb-6">
                                    <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-semibold rounded-full uppercase tracking-wider" x-text="videoData?.platform"></span>
                                    <a :href="videoData?.original_url" target="_blank" class="text-sm text-gray-500 hover:text-gray-300 flex items-center gap-1">
                                        Open Original
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                </div>

                                <div class="space-y-3">
                                    <template x-for="format in videoData?.formats" :key="format.format_id">
                                        <button 
                                            @click="startDownload(format.format_id)"
                                            class="w-full flex items-center justify-between p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/10 transition-all group"
                                        >
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center group-hover:bg-blue-100 dark:group-hover:bg-blue-900/50 transition-colors">
                                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                </div>
                                                <div class="text-left">
                                                    <div class="font-semibold" x-text="format.resolution"></div>
                                                    <div class="text-xs text-gray-500 uppercase" x-text="format.ext"></div>
                                                </div>
                                            </div>
                                            <div class="text-sm font-medium text-gray-500 group-hover:text-blue-600" x-text="format.filesize ? formatBytes(format.filesize) : 'Unknown size'"></div>
                                        </button>
                                    </template>
                                    
                                    <!-- Default Download Button (Best Quality) -->
                                    <button 
                                        @click="startDownload('best')"
                                        x-show="!videoData?.formats || videoData.formats.length === 0"
                                        class="w-full py-4 bg-gray-900 dark:bg-white text-white dark:text-black font-bold rounded-xl hover:opacity-90 transition-opacity"
                                    >
                                        Download Best Quality
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Processing/Downloading State -->
                    <div x-show="downloadJobId" class="mt-8 text-center" x-cloak>
                        <div class="max-w-md mx-auto bg-gray-50 dark:bg-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                            <h3 class="font-bold mb-4" x-text="downloadStatus === 'downloading' ? 'Processing Video...' : (downloadStatus === 'completed' ? 'Ready!' : 'Status')"></h3>
                            
                            <!-- Simple Progress Bar Animation -->
                            <div x-show="downloadStatus === 'downloading'" class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-2 overflow-hidden">
                                <div class="bg-blue-600 h-2.5 rounded-full animate-progress-indeterminate w-full origin-left transform scale-x-50"></div>
                            </div>

                            <p x-show="downloadStatus === 'failed'" class="text-red-500 text-sm mt-2">Download failed. Please try again.</p>

                            <div x-show="downloadStatus === 'completed' && downloadUrl" class="mt-4 animate-bounce-in">
                                <a :href="downloadUrl" download class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    Save Video
                                </a>
                                <button @click="reset" class="ml-4 text-gray-500 hover:text-gray-700 underline text-sm">Convert Another</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Features Grid -->
            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
                 <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/50">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mb-4 text-purple-600 dark:text-purple-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold mb-2 dark:text-gray-200">Lightning Fast</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Optimized processing engine ensures your videos are ready in seconds, not minutes.</p>
                 </div>
                 <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/50">
                    <div class="w-12 h-12 bg-pink-100 dark:bg-pink-900/30 rounded-lg flex items-center justify-center mb-4 text-pink-600 dark:text-pink-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold mb-2 dark:text-gray-200">High Quality</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Download in up to 4K resolution. We preserve the original quality of the source.</p>
                 </div>
                 <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/50">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mb-4 text-green-600 dark:text-green-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold mb-2 dark:text-gray-200">Secure & Private</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">No logs kept. Files are automatically deleted after 1 hour. Your privacy is our priority.</p>
                 </div>
            <!-- GDPR & Footer -->
            <div class="mt-12 border-t border-gray-200 dark:border-gray-700 pt-8 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
                <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg inline-block px-10">
                     <p class="text-xs text-yellow-800 dark:text-yellow-200 font-medium flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        GDPR Notice: We do not log submitted URLs or store user data permanently. Videos are auto-deleted after 24 hours.
                     </p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
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
                    this.downloadStatus = 'downloading'; // Optimistic UI
                    
                    try {
                        const response = await fetch('{{ route('download.start') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ 
                                url: this.url,
                                format: formatId // Note: Backend currently ignores format and grabs best, but good to have in protocol
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
                            const data = await response.json();

                            this.downloadStatus = data.status;

                            if (data.status === 'completed') {
                                this.downloadUrl = data.file_url;
                                clearInterval(this.statusInterval);
                            }
                            
                            if (data.status === 'failed') {
                                this.error = data.error || 'Download failed';
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
    </style>
    @endpush
</x-app-layout>
