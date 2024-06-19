<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Device;
use App\Models\EventLog;
use Illuminate\Console\Scheduling\Event;

class DeviceUpdatedNotification extends Mailable
{
    use Queueable, SerializesModels;

    protected String $event;
    /**
     * Create a new message instance.
     */
    public function __construct(String $event)
    {
        $this->event = $event;
        
    }
   
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: env('MAIL_FROM_ADDRESS'), // Set the sender's email and name
            to: [env('MAIL_TO_ADDRESS')], // Assuming the device model has an owner_email attribute
            subject: $this->event
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.deviceUpdated', // The Blade view for the email content
            with: [
                'deviceName' => $this->event, // Pass the device name to the view
            ]
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
