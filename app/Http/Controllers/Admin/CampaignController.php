<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendCampaignJob;
use App\Mail\NewCampaignMail;
use App\Models\Campaign;
use App\Models\Post;
use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class CampaignController extends Controller
{
    /**
     * Display a listing of campaigns.
     */
    public function index(): View
    {
        $campaigns = Campaign::withCount('posts')
            ->latest()
            ->paginate(15);

        return view('admin.campaigns.index', compact('campaigns'));
    }

    /**
     * Show the form for creating a new campaign.
     */
    public function create(): View
    {
        // Fetch latest 20 published posts for selection
        $posts = Post::published()
            ->latest('published_at')
            ->take(20)
            ->get();

        return view('admin.campaigns.create', compact('posts'));
    }

    /**
     * Store a newly created campaign in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'posts' => ['required', 'array', 'min:1'],
            'posts.*' => ['exists:posts,id'],
        ], [
            'subject.required' => 'عنوان البريد مطلوب.',
            'title.required' => 'العنوان الداخلي مطلوب.',
            'content.required' => 'النص التمهيدي مطلوب.',
            'posts.required' => 'يجب اختيار مقال واحد على الأقل.',
            'posts.min' => 'يجب اختيار مقال واحد على الأقل.',
            'posts.*.exists' => 'أحد المقالات المختارة غير موجود.',
        ]);

        // Create the campaign
        $campaign = Campaign::create([
            'subject' => $validated['subject'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => Campaign::STATUS_DRAFT,
        ]);

        // Attach selected posts
        $campaign->posts()->attach($validated['posts']);

        return redirect()
            ->route('admin.campaigns.show', $campaign)
            ->with('success', 'تم إنشاء الحملة البريدية بنجاح. يمكنك الآن معاينتها وإرسالها.');
    }

    /**
     * Display the specified campaign (Campaign Cockpit).
     */
    public function show(Campaign $campaign): View
    {
        $campaign->load('posts');
        $subscriberCount = Subscriber::active()->count();

        return view('admin.campaigns.show', compact('campaign', 'subscriberCount'));
    }

    /**
     * Show the form for editing the specified campaign.
     */
    public function edit(Campaign $campaign): View
    {
        $campaign->load('posts');
        
        $posts = Post::published()
            ->latest('published_at')
            ->take(20)
            ->get();

        $selectedPostIds = $campaign->posts->pluck('id')->toArray();

        return view('admin.campaigns.edit', compact('campaign', 'posts', 'selectedPostIds'));
    }

    /**
     * Update the specified campaign in storage.
     */
    public function update(Request $request, Campaign $campaign): RedirectResponse
    {
        // Only allow editing draft campaigns
        if ($campaign->isSent()) {
            return redirect()
                ->route('admin.campaigns.index')
                ->with('error', 'لا يمكن تعديل حملة تم إرسالها.');
        }

        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'posts' => ['required', 'array', 'min:1'],
            'posts.*' => ['exists:posts,id'],
        ], [
            'subject.required' => 'عنوان البريد مطلوب.',
            'title.required' => 'العنوان الداخلي مطلوب.',
            'content.required' => 'النص التمهيدي مطلوب.',
            'posts.required' => 'يجب اختيار مقال واحد على الأقل.',
            'posts.min' => 'يجب اختيار مقال واحد على الأقل.',
            'posts.*.exists' => 'أحد المقالات المختارة غير موجود.',
        ]);

        $campaign->update([
            'subject' => $validated['subject'],
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        // Sync selected posts
        $campaign->posts()->sync($validated['posts']);

        return redirect()
            ->route('admin.campaigns.show', $campaign)
            ->with('success', 'تم تحديث الحملة البريدية بنجاح.');
    }

    /**
     * Remove the specified campaign from storage.
     */
    public function destroy(Campaign $campaign): RedirectResponse
    {
        $campaign->posts()->detach();
        $campaign->delete();

        return redirect()
            ->route('admin.campaigns.index')
            ->with('success', 'تم حذف الحملة البريدية بنجاح.');
    }

    /**
     * Send a test email to the specified address.
     */
    public function sendTest(Request $request, Campaign $campaign): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح.',
        ]);

        try {
            // Load campaign posts
            $campaign->load('posts');
            
            // Create a temporary subscriber for test email (generates valid unsubscribe link)
            // We use firstOrCreate to handle repeated test sends to the same email
            $testSubscriber = Subscriber::withTrashed()
                ->firstOrCreate(
                    ['email' => $validated['email']],
                    ['is_active' => false] // Test subscribers are inactive by default
                );
            
            // Ensure the test subscriber has an unsubscribe token
            $testSubscriber->ensureUnsubscribeToken();
            
            // Send test email via newsletter mailer (not queued, immediate send)
            Mail::mailer(config('newsletter.mailer'))
                ->to($validated['email'])
                ->send(new NewCampaignMail($campaign, $testSubscriber));

            return redirect()
                ->route('admin.campaigns.show', $campaign)
                ->with('success', 'تم إرسال النسخة التجريبية بنجاح إلى ' . $validated['email']);
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.campaigns.show', $campaign)
                ->with('error', 'حدث خطأ أثناء إرسال البريد: ' . $e->getMessage());
        }
    }

    /**
     * Send the campaign to all active subscribers.
     */
    public function send(Campaign $campaign): RedirectResponse
    {
        // Check if campaign is already sent
        if ($campaign->isSent()) {
            return redirect()
                ->route('admin.campaigns.show', $campaign)
                ->with('error', 'تم إرسال هذه الحملة مسبقاً.');
        }

        // Check if there are active subscribers
        $subscriberCount = Subscriber::active()->count();
        if ($subscriberCount === 0) {
            return redirect()
                ->route('admin.campaigns.show', $campaign)
                ->with('error', 'لا يوجد مشتركين نشطين لإرسال الحملة إليهم.');
        }

        // Update status to 'sending'
        $campaign->update(['status' => Campaign::STATUS_SENDING]);

        // Dispatch the job to send emails in the background
        SendCampaignJob::dispatch($campaign);

        return redirect()
            ->route('admin.campaigns.show', $campaign)
            ->with('success', 'بدأ إرسال الحملة البريدية في الخلفية. سيتم إعلامك عند الانتهاء.');
    }
}
