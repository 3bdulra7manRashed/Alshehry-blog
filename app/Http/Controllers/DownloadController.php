<?php

namespace App\Http\Controllers;

use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    /**
     * Handle the public download request.
     */
    public function download($slug)
    {
        $download = Download::where('slug', $slug)->firstOrFail();

        // Check if file is active (enabled)
        if (!$download->is_active) {
            abort(404, 'هذا الملف غير متاح حالياً');
        }

        // Check if file exists in storage
        if (!Storage::disk('public')->exists($download->file_path)) {
            abort(404, 'الملف غير موجود');
        }

        // Increment downloads count
        $download->increment('downloads_count');

        // Return download response
        return Storage::disk('public')->download($download->file_path, $download->title . '.' . pathinfo($download->file_path, PATHINFO_EXTENSION));
    }
}
