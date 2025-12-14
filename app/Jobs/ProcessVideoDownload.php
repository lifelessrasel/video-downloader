<?php

namespace App\Jobs;

use App\Services\VideoDownloaderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class ProcessVideoDownload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $url;
    public $jobId;
    public $format;
    public $startTime;
    public $endTime;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 600;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * Create a new job instance.
     */
    public function __construct(string $url, string $jobId, string $format = 'best', ?string $startTime = null, ?string $endTime = null)
    {
        $this->url = $url;
        $this->jobId = $jobId;
        $this->format = $format;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }

    /**
     * Execute the job.
     */
    public function handle(VideoDownloaderService $downloader): void
    {
        Cache::put("download_status:{$this->jobId}", [
            'status' => 'downloading',
            'progress' => 0,
        ], 600);

        try {
            // Get Info first (lightweight) to get title for stats
            $info = $downloader->getVideoInfo($this->url);
            $title = $info['title'] ?? 'Unknown Video';

            // Download
            $filePath = $downloader->downloadVideo($this->url, $this->jobId, $this->format, $this->startTime, $this->endTime);
            
            // Record Stats
            // 1. All Time Top
            Redis::zincrby('stats:top_downloads:all_time', 1, $title);
            
            // 2. 7 Days (We store in a generic sorted set for today, log rotation handles cleanup or we just keep growing for MVP)
            // A better approach for "Top 7 Days" is storing daily ZSETS: stats:top_downloads:YYYY-MM-DD
            // And aggregating them on read.
            $today = now()->format('Y-m-d');
            Redis::zincrby("stats:top_downloads:daily:{$today}", 1, $title);
            Redis::expire("stats:top_downloads:daily:{$today}", 86400 * 8); // Expire after 8 days

            // One-time download URL (Relative, so it works on any domain)
            $downloadUrl = route('download.serve', ['jobId' => $this->jobId], false);

            // Cache 'job_path' separately so Controller can find it securely without exposing path in URL
            Cache::put("download_path:{$this->jobId}", $filePath, 3600);

            Cache::put("download_status:{$this->jobId}", [
                'status' => 'completed',
                'file_url' => $downloadUrl,
            ], 3600); 

        } catch (\Exception $e) {
            Log::error("Download job failed: " . $e->getMessage());
            Cache::put("download_status:{$this->jobId}", [
                'status' => 'failed',
                'error' => $e->getMessage(),
            ], 600);
            
            $this->fail($e);
        }
    }
}
