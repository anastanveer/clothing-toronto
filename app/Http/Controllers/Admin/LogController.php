<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class LogController extends Controller
{
    public function __invoke(): View
    {
        $logPath = storage_path('logs/laravel.log');
        $entries = collect();

        if (File::exists($logPath)) {
            $lines = @file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
            $entries = collect(array_reverse($lines))->take(200);
        }

        return view('admin.logs.index', [
            'entries' => $entries,
            'logPath' => $logPath,
        ]);
    }
}
