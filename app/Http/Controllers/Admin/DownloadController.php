<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DownloadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $downloads = Download::latest()->paginate(20);
        return view('admin.downloads.index', compact('downloads'));
    }

    /**
     * Generate an Arabic-friendly slug.
     * 
     * @param string $title
     * @return string
     */
    protected function generateArabicSlug(string $title): string
    {
        // Trim whitespace
        $slug = trim($title);
        
        // Replace spaces with dashes
        $slug = preg_replace('/\s+/', '-', $slug);
        
        // Remove special characters (keep Arabic, English, numbers, and dashes)
        $slug = preg_replace('/[^\p{Arabic}a-zA-Z0-9\-]/u', '', $slug);
        
        // Remove multiple consecutive dashes
        $slug = preg_replace('/-+/', '-', $slug);
        
        // Trim dashes from start and end
        $slug = trim($slug, '-');
        
        return $slug;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|max:51200', // Max 50MB
        ]);

        $file = $request->file('file');
        
        // Upload file to storage/app/public/downloads
        $path = $file->store('downloads', 'public');
        $mimeType = $file->getClientMimeType();
        
        // Generate Arabic-friendly unique slug
        $slug = $this->generateArabicSlug($request->title);
        
        // Ensure uniqueness - append timestamp if exists
        if (Download::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . time();
        }

        Download::create([
            'title' => $request->title,
            'slug' => $slug,
            'file_path' => $path,
            'mime_type' => $mimeType,
            'is_active' => true,
            'downloads_count' => 0,
        ]);

        return redirect()->back()->with('success', 'تم رفع الملف بنجاح');
    }

    /**
     * Toggle the active status of the download.
     */
    public function toggle(Download $download)
    {
        $download->update([
            'is_active' => !$download->is_active,
        ]);

        $status = $download->is_active ? 'مفعّل' : 'معطّل';
        
        return redirect()->back()->with('success', "تم تحديث حالة الملف إلى: {$status}");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Download $download)
    {
        // Delete file from storage
        if ($download->file_path && Storage::disk('public')->exists($download->file_path)) {
            Storage::disk('public')->delete($download->file_path);
        }

        $download->delete();

        return redirect()->back()->with('success', 'تم حذف الملف بنجاح');
    }
}
