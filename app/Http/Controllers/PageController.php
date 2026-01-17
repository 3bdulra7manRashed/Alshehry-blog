<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Mail\ContactMessage;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        $recentPosts = Post::published()
            ->latest('published_at')
            ->limit(5)
            ->get();

        $categories = Category::withCount('posts')
            ->orderBy('order_column')
            ->get();

        // Fetch the site owner (super admin or user ID 1)
        $user = \App\Models\User::where('is_super_admin', true)->orWhere('id', 1)->first();

        return view('pages.about', compact('recentPosts', 'categories', 'user'));
    }

    public function contact(): View
    {
        $recentPosts = Post::published()
            ->latest('published_at')
            ->limit(5)
            ->get();

        $categories = Category::withCount('posts')
            ->orderBy('order_column')
            ->get();

        return view('pages.contact', compact('recentPosts', 'categories'));
    }

    public function sendContact(ContactFormRequest $request)
    {
        $validated = $request->validated();

        try {
            // Save message to database first
            $contactMessage = \App\Models\ContactMessage::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'message' => $validated['message'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Send email to the site owner
            Mail::to(config('mail.from.address'))->send(new ContactMessage($validated));

            Log::info('Contact form submitted and saved', [
                'id' => $contactMessage->id,
                'from' => $validated['email'],
                'name' => $validated['name'],
            ]);

            return back()->with('success', 'شكراً لرسالتك. سيتم الرد عليك قريباً!');
        } catch (\Exception $e) {
            Log::error('Failed to process contact form', [
                'error' => $e->getMessage(),
                'from' => $validated['email'],
            ]);

            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى لاحقاً.');
        }
    }
}


