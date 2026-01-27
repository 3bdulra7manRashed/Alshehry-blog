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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|max:51200', // Max 50MB
        ]);

        $file = $request->file('file');
        
        // Upload file to public/uploads/files
        // Using 'public' disk commonly points to storage/app/public, 
        // to make it accessible via web if needed, or keeping it private.
        // The prompt says "Upload the file to public/uploads/files (or storage)."
        // We will use the 'uploads' directory in the public disk, or 'public' disk with 'uploads/files' path.
        // Let's assume the 'public' disk is configured to link to public/storage.
        // We'll store it in 'downloads' folder for better organization.
        
        $path = $file->store('downloads', 'public');
        $mimeType = $file->getClientMimeType();
        
        // Generate unique slug
        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        while (Download::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        Download::create([
            'title' => $request->title,
            'slug' => $slug,
            'file_path' => $path,
            'mime_type' => $mimeType,
            'downloads_count' => 0,
        ]);

        return redirect()->back()->with('success', 'تم رفع الملف بنجاح');
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
