<?php

namespace App\Mail;

use App\Models\Campaign;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewCampaignMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The unsubscribe link for this specific recipient.
     */
    public string $unsubscribeLink;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Campaign $campaign,
        public Subscriber $subscriber
    ) {
        // Load the posts relationship
        $this->campaign->load('posts');
        
        // Generate the unsubscribe link for this specific subscriber
        $this->unsubscribeLink = $this->subscriber->generateUnsubscribeLink();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                env('NEWSLETTER_MAIL_FROM_ADDRESS', 'newsletter@alshehrysaleh.com'),
                env('NEWSLETTER_MAIL_FROM_NAME', 'مدونة صالح الشهري')
            ),
            subject: $this->campaign->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.campaign',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
