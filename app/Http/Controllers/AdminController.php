<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        // Fetch stats from Redis (simple implementation)
        $totalVisits = Redis::get('stats:visits:total') ?? 0;
        
        // 7 Day stats (Visits)
        $labels = [];
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('M d');
            $data[] = (int) (Redis::get("stats:visits:day:{$date}") ?? 0);
        }
        
        // Top Downloads (All Time)
        $topDownloadsAllTime = Redis::zrevrange('stats:top_downloads:all_time', 0, 9, 'WITHSCORES');
        
        // Top Downloads (7 Days) - We need to Aggregate daily keys.
        // Let's create a temporary key unioning the last 7 days.
        $sevenDayKeys = [];
        for ($i = 0; $i < 7; $i++) {
            $sevenDayKeys[] = "stats:top_downloads:daily:" . now()->subDays($i)->format('Y-m-d');
        }
        
        Redis::zunionstore('stats:top_downloads:7days_agg', $sevenDayKeys);
        $topDownloads7Days = Redis::zrevrange('stats:top_downloads:7days_agg', 0, 9, 'WITHSCORES');
        // optional: expire agg key
        Redis::expire('stats:top_downloads:7days_agg', 60);

        return view('admin.dashboard', [
            'totalVisits' => $totalVisits,
            'usersCount' => User::count(),
            'chartLabels' => $labels,
            'chartData' => $data,
            'topDownloadsAllTime' => $topDownloadsAllTime,
            'topDownloads7Days' => $topDownloads7Days
        ]);
    }
}
