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

        // Increment downloads count
        $download->increment('downloads_count');

        // Check if file exists
        if (!Storage::disk('public')->exists($download->file_path)) {
            abort(404, 'الملف غير موجود');
        }

        // Return download response
        return Storage::disk('public')->download($download->file_path, $download->title . '.' . pathinfo($download->file_path, PATHINFO_EXTENSION));
    }
}
