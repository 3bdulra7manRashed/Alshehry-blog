<?php

namespace App\Jobs;

use App\Mail\NewCampaignMail;
use App\Models\Campaign;
use App\Models\Subscriber;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 60;

    /**
     * The campaign instance.
     *
     * @var \App\Models\Campaign
     */
    protected Campaign $campaign;

    /**
     * Create a new job instance.
     */
    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Load the campaign with its posts
        $this->campaign->load('posts');
        
        $successCount = 0;
        $failedCount = 0;
        
        // Fetch active subscribers in chunks for performance
        Subscriber::active()->chunk(100, function ($subscribers) use (&$successCount, &$failedCount) {
            foreach ($subscribers as $subscriber) {
                try {
                    // Queue each email for sending via configured newsletter mailer
                    Mail::mailer(config('newsletter.mailer'))
                        ->to($subscriber->email)
                        ->queue(new NewCampaignMail($this->campaign, $subscriber));
                    $successCount++;
                } catch (\Exception $e) {
                    $failedCount++;
                    Log::error("Failed to queue email for subscriber {$subscriber->email}: " . $e->getMessage());
                }
            }
        });
        
        // Mark the campaign as sent
        $this->campaign->markAsSent();
        
        Log::info("Campaign '{$this->campaign->subject}' sent. Success: {$successCount}, Failed: {$failedCount}");
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("SendCampaignJob failed for campaign ID {$this->campaign->id}: " . $exception->getMessage());
    }
}
