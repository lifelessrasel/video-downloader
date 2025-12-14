<?php

namespace App\Http\Controllers;

use App\Services\VideoDownloaderService;
use App\Jobs\ProcessVideoDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DownloadController extends Controller
{
    protected $downloader;

    public function __construct(VideoDownloaderService $downloader)
    {
        $this->downloader = $downloader;
    }

    public function index()
    {
        return view('dashboard');
    }

    public function preview(Request $request)
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        try {
            $info = $this->downloader->getVideoInfo($request->url);
            return response()->json(['success' => true, 'data' => $info]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function startDownload(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'format' => 'nullable|string',
            'start_time' => 'nullable|string', // Regex validation could be added: ^(\d{1,2}:)?\d{2}:\d{2}$
            'end_time' => 'nullable|string'
        ]);

        $jobId = (string) Str::uuid();
        
        // Initialize status immediately so frontend doesn't get 404
        Cache::put("download_status:{$jobId}", ['status' => 'queued', 'progress' => 0], 600);

        // Dispatch job (pass format, start, end)
        ProcessVideoDownload::dispatch(
            $request->url, 
            $jobId, 
            $request->format ?? 'best',
            $request->start_time,
            $request->end_time
        );
        
        return response()->json(['success' => true, 'job_id' => $jobId]);
    }

    public function checkStatus($jobId)
    {
        $status = Cache::get("download_status:{$jobId}");

        if (!$status) {
            return response()->json(['status' => 'not_found'], 404);
        }

        return response()->json($status);
    }
    
    public function serveFile($jobId) {
        $path = Cache::get("download_path:{$jobId}");
        
        if (!$path || !file_exists($path)) {
            if ($path) Log::warning("File missing for job {$jobId} at {$path}");
            abort(404, 'File expired or invalid.');
        }
        
        // Serve and delete immediately
        return response()->download($path)->deleteFileAfterSend(true);
    }
}
