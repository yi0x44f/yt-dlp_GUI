<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class YoutubeController extends Controller
{
    public function download(Request $request)
    {
        /* ---------- ① 取參數 ---------- */
        $url        = $request->string('url')->value();
        $format     = $request->string('format')->value();      // mp4 / mp3
        $resolution = $request->string('resolution')->value();    // 1080, 720,
        $start_time = $request->string('start_time')->value();  // HH:MM:SS or null
        $end_time   = $request->string('end_time')->value();    // HH:MM:SS or null
        $output_dir = storage_path('app/public/video/');
        /* ---------- ② 檢查基本參數 ---------- */
        if (!$url || !in_array($format, ['mp4', 'mp3'])) {
            return response()->json(['error' => 'Invalid parameters'], 422);
        }

        /* ---------- ③ 組 download-sections ---------- */
        $section = '';
        if ($start_time !== '' || $end_time !== '') {
            $range = ($start_time ?: '0:00:00') . '-' . ($end_time ?: '');
            $section = '--download-sections "*' . $range . '"';
        }

        /* ---------- ④ 組 yt-dlp 命令 ---------- */
        if ($format === 'mp3') {
            $cmd = sprintf(
                'yt-dlp -x --audio-format mp3 --path %s %s --restrict-filenames %s',
                $output_dir,
                $section,
                escapeshellarg($url)
            );
        } else { // mp4
            // Step-A：先嘗試 H.264 (avc) 版本，解析度 ≤ $resolution
            $selectAvc = sprintf(
                'bv*[vcodec^=avc][height<=%s]+ba/b',
                $resolution
            );

            // Step-B：若找不到 (fallback) 就抓最佳 video+audio，再重編碼成 mp4
            // 注意：--recode-video mp4 會在需要時才啟動轉碼
            $cmd = sprintf(
                'yt-dlp -f "%s / bv+ba/b" ' .
                '--merge-output-format mp4 --recode-video mp4 --path %s %s --restrict-filenames %s',
                $selectAvc,
                $output_dir,
                $section,
                escapeshellarg($url)
            );
        }

        /* ---------- ⑤ 執行 ---------- */
        $result = Process::run($cmd);


        if (!$result->successful()) {
            return redirect('/')->withErrors(['message' => '下載失敗：' . $result->errorOutput()]);
        }


        /* ---------- ⑥ 返回成功響應 ---------- */
        return redirect('/')->with('message', '下載成功');
    }
}
