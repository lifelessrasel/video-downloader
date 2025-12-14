<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    private $platforms = [
        'youtube' => [
            'name' => 'YouTube',
            'title' => 'YouTube Video Downloader - Download YouTube Videos 1080p, 4K',
            'description' => 'Best free YouTube Video Downloader. Save YouTube videos in MP4, MP3, HD, 4K quality globally. No software required.',
            'keywords' => 'youtube downloader, download youtube video, youtube to mp4, youtube 4k downloader'
        ],
        'tiktok' => [
            'name' => 'TikTok',
            'title' => 'TikTok Video Downloader (No Watermark) - Free & Fast',
            'description' => 'Download TikTok videos without watermark. Save TikToks in HD MP4 format efficiently. Fastest TikTok downloader 2024.',
            'keywords' => 'tiktok downloader, tiktok no watermark, download tiktok video, save tiktok'
        ],
        'instagram' => [
            'name' => 'Instagram',
            'title' => 'Instagram Video Downloader - Reels, Stories, IGTV',
            'description' => 'Download Instagram Videos, Reels, Stories, and IGTV. Save IG content anonymously and securely in high quality.',
            'keywords' => 'instagram downloader, download instagram reels, save ig stories, instagram video saver'
        ],
        'facebook' => [
            'name' => 'Facebook',
            'title' => 'Facebook Video Downloader - Download FB Videos HD',
            'description' => 'Online Facebook Video Downloader. Download public and private Facebook videos in 1080p, 4K. seamless and free.',
            'keywords' => 'facebook downloader, fb video download, save facebook video, facebook hd downloader'
        ],
        'twitter' => [
            'name' => 'Twitter / X',
            'title' => 'Twitter Video Downloader - Save X Videos & GIFs',
            'description' => 'Download Twitter (X) videos and GIFs in MP4. Fast and easy Twitter media saver.',
            'keywords' => 'twitter downloader, download x video, save twitter gif, twitter video saver'
        ],
        'pinterest' => [
            'name' => 'Pinterest',
            'title' => 'Pinterest Video Downloader - Save Pins & Videos',
            'description' => 'Download Pinterest Videos and GIFs in high quality. Save Pins to your device easily.',
            'keywords' => 'pinterest downloader, pinterest video download, save pin video, pinterest to mp4'
        ],
        'reddit' => [
            'name' => 'Reddit',
            'title' => 'Reddit Video Downloader - Save Reddit Videos with Audio',
            'description' => 'Download Reddit videos with sound in HD. Save content from any subreddit quickly and free.',
            'keywords' => 'reddit downloader, download reddit video, save reddit video, reddit to mp4'
        ],
        'bilibili' => [
            'name' => 'Bilibili',
            'title' => 'Bilibili Video Downloader - Download HD Bilibili Videos',
            'description' => 'Download Bilibili videos in 1080p/4K HD. Fast, free, and unlimited Bilibili downloader.',
            'keywords' => 'bilibili downloader, download bilibili video, bilibili to mp4, save bilibili'
        ],
        'spotify' => [
            'name' => 'Spotify',
            'title' => 'Spotify MP3 Downloader - Convert Spotify to MP3',
            'description' => 'Download Spotify songs and playlists to MP3. Convert your favorite music for offline listening.',
            'keywords' => 'spotify downloader, spotify to mp3, download spotify songs, spotify converter'
        ],
        'soundcloud' => [
            'name' => 'SoundCloud',
            'title' => 'SoundCloud Downloader - Save Tracks & Music',
            'description' => 'Download SoundCloud tracks in MP3 format. Save music and playlists from SoundCloud for free.',
            'keywords' => 'soundcloud downloader, download soundcloud mp3, save soundcloud music'
        ],
        'vimeo' => [
            'name' => 'Vimeo',
            'title' => 'Vimeo Video Downloader - Download Vimeo Videos 4K',
            'description' => 'Download private and public Vimeo videos in 4K, 1080p. Best Vimeo downloader online.',
            'keywords' => 'vimeo downloader, download vimeo video, save vimeo, vimeo mp4'
        ],
        'twitch' => [
            'name' => 'Twitch',
            'title' => 'Twitch Video Downloader - Save Clips & VODs',
            'description' => 'Download Twitch clips and past broadcasts (VODs). Save Twitch streams offline.',
            'keywords' => 'twitch downloader, download twitch clip, save twitch vod'
        ],
        'linkedin' => [
            'name' => 'LinkedIn',
            'title' => 'LinkedIn Video Downloader - Save Professional Videos',
            'description' => 'Download videos from LinkedIn posts and learning courses. Save MP4 files from LinkedIn.',
            'keywords' => 'linkedin downloader, download linkedin video, save linkedin video'
        ],
        'threads' => [
            'name' => 'Threads',
            'title' => 'Threads Video Downloader - Save Threads Videos',
            'description' => 'Download videos from Instagram Threads. Save Threads app videos in HD.',
            'keywords' => 'threads downloader, download threads video, save threads media'
        ],
        'tumblr' => [
            'name' => 'Tumblr',
            'title' => 'Tumblr Video Downloader',
            'description' => 'Download videos and GIFs from Tumblr blogs. Fast and free Tumblr downloader.',
            'keywords' => 'tumblr downloader, download tumblr video, save tumblr gif'
        ],
        'vk' => [
            'name' => 'VK',
            'title' => 'VK Video Downloader - Save VKontakte Videos',
            'description' => 'Download videos from VK (VKontakte) in HD. Save Russian social media videos easily.',
            'keywords' => 'vk downloader, download vk video, save vk video'
        ],
        'likee' => [
            'name' => 'Likee',
            'title' => 'Likee Video Downloader (No Watermark)',
            'description' => 'Download Likee videos without watermark. Save Likee short videos in HD.',
            'keywords' => 'likee downloader, likee no watermark, download likee video'
        ],
        'dailymotion' => [
            'name' => 'Dailymotion',
            'title' => 'Dailymotion Video Downloader',
            'description' => 'Download Dailymotion videos in Full HD. Save videos from Dailymotion offline.',
            'keywords' => 'dailymotion downloader, download dailymotion video'
        ],
        'bandcamp' => [
            'name' => 'Bandcamp',
            'title' => 'Bandcamp Downloader - Save Music',
            'description' => 'Download tracks and albums from Bandcamp. Save independent music in MP3.',
            'keywords' => 'bandcamp downloader, download bandcamp mp3'
        ],
        'imdb' => [
            'name' => 'IMDb',
            'title' => 'IMDb Video Downloader - Save Trailers',
            'description' => 'Download movie trailers and interviews from IMDb without watermark.',
            'keywords' => 'imdb downloader, download imdb video'
        ],
        'mashable' => [
            'name' => 'Mashable',
            'title' => 'Mashable Video Downloader',
            'description' => 'Download news and tech videos from Mashable.',
            'keywords' => 'mashable downloader, download mashable video'
        ],
        'ninegag' => [ // keys cannot start with number easily in some parsers, but string key is fine.
            'name' => '9GAG',
            'title' => '9GAG Video Downloader',
            'description' => 'Download videos and GIFs from 9GAG. Save meme videos easily.',
            'keywords' => '9gag downloader, download 9gag video'
        ],
        'ted' => [
            'name' => 'TED',
            'title' => 'TED Talks Downloader',
            'description' => 'Download inspirational TED Talks videos for offline viewing.',
            'keywords' => 'ted downloader, download ted talk'
        ],
        // Fallback / Generic
        'generic' => [
            'name' => 'Social Media',
            'title' => 'Social Media Video Downloader - All-in-One Video Saver',
            'description' => 'Universal Video Downloader for YouTube, TikTok, Instagram, Facebook, and 1000+ sites. Free, fast, and secure.',
            'keywords' => 'video downloader, online video downloader, social media downloader, mp4 downloader'
        ]
    ];

    public function index()
    {
        $this->recordVisit();
        return view('welcome', [
            'seo' => $this->platforms['generic'],
            'platform_slug' => null
        ]);
    }

    public function platform($slug)
    {
        // Parse "facebook-video-downloader" -> "facebook"
        $platformKey = str_replace('-video-downloader', '', $slug);

        if (!array_key_exists($platformKey, $this->platforms)) {
            abort(404);
        }

        $this->recordVisit();

        return view('welcome', [
            'seo' => $this->platforms[$platformKey],
            'platform_slug' => $platformKey
        ]);
    }

    public function sitemap()
    {
        $baseUrl = config('app.url');
        $urls = [
            route('home'),
            route('about'),
            route('contact'),
            route('privacy'),
            route('terms'),
            route('dmca'),
        ];

        foreach (array_keys($this->platforms) as $key) {
            if ($key === 'generic') continue;
            $urls[] = url("/{$key}-video-downloader");
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        foreach ($urls as $url) {
            $xml .= '<url>';
            $xml .= "<loc>{$url}</loc>";
            $xml .= "<lastmod>" . date('Y-m-d') . "</lastmod>";
            $xml .= "<changefreq>daily</changefreq>";
            $xml .= "<priority>0.8</priority>";
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml)->header('Content-Type', 'text/xml');
    }

    private function recordVisit()
    {
        $today = now()->format('Y-m-d');
        Redis::incr('stats:visits:total');
        Redis::incr('stats:visits:day:' . $today);
        Redis::expire('stats:visits:day:' . $today, 86400 * 30);
    }
}
