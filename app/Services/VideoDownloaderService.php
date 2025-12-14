<?php

namespace App\Services;

use Illuminate\Support\Facades\Process;
use Illuminate\Process\Pool;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process as SymfonyProcess;
use Exception;

class VideoDownloaderService
{
    protected string $downloadPath;

    public function __construct()
    {
        $this->downloadPath = storage_path('app/public/downloads');
        if (!file_exists($this->downloadPath)) {
            mkdir($this->downloadPath, 0755, true);
        }
    }

    /**
     * Get video metadata (title, thumbnail, duration, formats).
     *
     * @param string $url
     * @return array
     * @throws Exception
     */
    public function getVideoInfo(string $url): array
    {
        $command = [
            'yt-dlp',
            '--dump-json',
            '--no-playlist',
            '--user-agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            $url
        ];

        $process = new SymfonyProcess($command);
        $process->setTimeout(60);
        $process->run();

        if (!$process->isSuccessful()) {
            Log::error('yt-dlp info failed', ['error' => $process->getErrorOutput(), 'url' => $url]);
            throw new Exception('Failed to fetch video info: ' . $process->getErrorOutput());
        }

        $output = $process->getOutput();
        $start = strpos($output, '{');
        if ($start === false) {
             throw new Exception('Invalid JSON output from yt-dlp');
        }
        
        // Handle potential extra output before JSON
        $jsonStr = substr($output, $start);
        $data = json_decode($jsonStr, true);

        if (!$data) {
             throw new Exception('Failed to decode JSON metadata');
        }

        // Check platform and Fix for Spotify (Audio Only)
        $platform = $data['extractor_key'] ?? 'Unknown';
        $duration = $data['duration'] ?? 0;
        
        $formats = $this->filterFormats($data['formats'] ?? []);

        // Estimate filesize if missing
        foreach ($formats as &$format) {
            if (($format['filesize'] ?? 0) === 0) {
                // Try tbr * duration / 8 * 1024 ...
                // tbr is in kbits/s usually in yt-dlp
                $tbr = $format['tbr'] ?? 0;
                if ($tbr > 0 && $duration > 0) {
                     // kbit/s * seconds = kbits. / 8 = kBytes. * 1024 = Bytes.
                     // Wait, kbits/s * 1000 = bits/s. * duration = bits. / 8 = Bytes.
                     // yt-dlp tbr is usually kbit/s.
                     $estimated = ($tbr * 1000 * $duration) / 8;
                     $format['filesize'] = (int)$estimated;
                }
            }
        }
        unset($format); // break reference

        // If Spotify (or other audio-only sites) and no formats found (common in direct stream extractors), inject Audio option
        if (empty($formats) || stripos($platform, 'spotify') !== false || stripos($url, 'spotify.com') !== false) {
                 // Wipe other formats if it's Spotify, ensuring we only show Audio
                 $formats = [[
                    'format_id' => 'audio_best',
                    'resolution' => 'Audio Only (MP3)',
                    'ext' => 'mp3',
                    'filesize' => 0,
                    'vcodec' => 'none'
                 ]];
        }
        
        return [
            'id' => $data['id'],
            'title' => $data['title'],
            'thumbnail' => $data['thumbnail'],
            'duration' => $data['duration'] ?? 0,
            'formats' => $formats,
            'platform' => $platform,
            'original_url' => $data['webpage_url'] ?? $url,
        ];
    }

    /**
     * Download video.
     *
     * @param string $url
     * @param string $jobId
     * @param string $format
     * @return string Absolute path to downloaded file
     * @throws Exception
     */
    /**
     * Download video.
     *
     * @param string $url
     * @param string $jobId
     * @param string $format
     * @param string|null $startTime (HH:MM:SS or SS)
     * @param string|null $endTime (HH:MM:SS or SS)
     * @return string Absolute path to downloaded file
     * @throws Exception
     */
    public function downloadVideo(string $url, string $jobId, string $format = 'best', ?string $startTime = null, ?string $endTime = null): string
    {
        // Template: downloads/{jobId}.%(ext)s
        $outputTemplate = $this->downloadPath . '/' . $jobId . '.%(ext)s';

        // Force Audio Best for Spotify
        if (stripos($url, 'spotify.com') !== false) {
            $format = 'audio_best';
        }

        // Helper to add args
        $args = [
            'yt-dlp',
            '--no-playlist',
        ];
        
        // Disable Aria2c for Facebook (issues with signed URLs/headers)
        if (stripos($url, 'facebook.com') === false && stripos($url, 'fb.watch') === false) {
            $args[] = '--downloader';
            $args[] = 'aria2c';
            $args[] = '--downloader-args';
            $args[] = 'aria2c:-c -j 8 -x 8 -s 8 -k 1M';
        }

        // Construct Format String and Extra Args
        if ($format === 'best') {
             $formatStr = 'bestvideo[ext=mp4]+bestaudio[ext=m4a]/best[ext=mp4]/best'; 
        } elseif ($format === 'audio_best') {
             // Audio Only Request
             $formatStr = 'bestaudio/best';
             $args[] = '--extract-audio';
             $args[] = '--audio-format';
             $args[] = 'mp3';
        } else {
             $formatStr = "{$format}+bestaudio/best";
        }

        $args[] = '-f';
        $args[] = $formatStr;
        $args[] = '-o';
        $args[] = $outputTemplate;
        $args[] = '--user-agent';
        $args[] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36';

        $command = $args;

        // Add Trim arguments if provided
        // Format: *start_time-end_time
        // The asterisk forces re-encoding if needed/accurate cutting, but usually just download-sections is enough.
        // yt-dlp syntax: --download-sections "*00:00:10-00:00:20"
        if ($startTime || $endTime) {
            $start = $startTime ?? 'inf'; // 'inf' is not start default, 0 is. But wait, emptiness means start.
            $start = $startTime ?: ''; 
            $end = $endTime ?: '';
            
            // If user provides "10" treat as seconds. If "00:10" treat as timestamp. 
            // yt-dlp handles both.
            
            $command[] = '--download-sections';
            $command[] = "*{$start}-{$end}";
            $command[] = '--force-keyframes-at-cuts'; // Ensure accurate cuts
        }
        
        $command[] = $url;

        $process = new SymfonyProcess($command);
        $process->setTimeout(600); // 10 minutes max
        $process->run();

        if (!$process->isSuccessful()) {
            Log::error('yt-dlp download failed', ['error' => $process->getErrorOutput(), 'url' => $url]);
            throw new Exception('Download failed: ' . $process->getErrorOutput());
        }

        // Find the file (extension might vary)
        $files = glob($this->downloadPath . '/' . $jobId . '.*');
        if (empty($files)) {
            throw new Exception('Downloaded file not found.');
        }

        return $files[0];
    }

    private function filterFormats(array $formats): array
    {
        $filtered = [];

        foreach ($formats as $f) {
            // Check if it is audio only
            $isAudio = (!isset($f['height']) && isset($f['acodec']) && $f['acodec'] !== 'none') || (isset($f['vcodec']) && $f['vcodec'] === 'none');
            
            $height = $f['height'] ?? 0;
            $filesize = $f['filesize'] ?? ($f['filesize_approx'] ?? 0);

            // Fallback: Estimate from Bitrate if duration is available (GLOBAL or passed? I'll use Tbr/Vbr)
            // Note: $duration isn't available here in scope easily unless passed. 
            // However, $f['tbr'] (Total Bitrate) is often available.
            // But we don't have duration in this loop.
            // Let's just trust tbr if available for comparative logic, but for UI display we return 0 and frontend shows N/A.
            // Wait, users WANT to see size.
            // Let's rely on Tbr * Duration if we can get duration.
            
            $ext = $f['ext'] ?? 'mp4';
            
            if ($isAudio) {
                // Audio Only Logic
                $key = "audio";
                // If existing is 0 and new is 0, we update. If new > existing, we update.
                if (!isset($filtered[$key]) || ($filesize > ($filtered[$key]['filesize'] ?? 0))) {
                    $filtered[$key] = [
                        'format_id' => 'audio_best', 
                        'resolution' => 'Audio Only (MP3)',
                        'ext' => 'mp3',
                        'filesize' => $filesize,
                        'vcodec' => 'none',
                        'tbr' => $f['tbr'] ?? 0 // Store bitrate for later estimation if needed
                    ];
                }
                continue;
            }

            // Video logic
            if ($height == 0) continue; 

            $key = "{$height}p";

            // Logic: Prefer format with Known Filesize > Unknown. Then Larger > Smaller.
            // If both unknown, prefer higher bitrate (tbr).
            $currentFilesize = $filtered[$key]['filesize'] ?? 0;
            $currentTbr = $filtered[$key]['tbr'] ?? 0;
            $newTbr = $f['tbr'] ?? 0;

            $shouldUpdate = !isset($filtered[$key]); // New key
            
            if (!$shouldUpdate) {
                if ($filesize > 0 && $currentFilesize == 0) {
                    $shouldUpdate = true; // Prefer known size
                } elseif ($filesize > 0 && $filesize > $currentFilesize) {
                    $shouldUpdate = true; // Prefer larger size (quality)
                } elseif ($filesize == 0 && $currentFilesize == 0) {
                    // Both unknown size, check bitrate
                    if ($newTbr > $currentTbr) {
                        $shouldUpdate = true;
                    }
                }
            }

            if ($shouldUpdate) {
                $filtered[$key] = [
                    'format_id' => $f['format_id'], 
                    'resolution' => $height . 'p',
                    'ext' => $ext,
                    'filesize' => $filesize, // Remains 0 if unknown
                    'vcodec' => $f['vcodec'] ?? '',
                    'tbr' => $newTbr
                ];
            }
        }

        // Sort by height descending (Audio will be at bottom usually as 0p, or we can separate)
        usort($filtered, fn($a, $b) => intval($b['resolution']) - intval($a['resolution']));

        return array_values($filtered);
    }
}
