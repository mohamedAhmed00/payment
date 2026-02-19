<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SendInvoiceMailLink extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(private string $url, private mixed $organization)
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $logo = $this->organization?->logo ?? 'logos/default_logo.jpeg';
        $logo = Storage::disk('public')->url($logo);
        return $this->from(env('MAIL_USERNAME'), 'PaymentHub')
            ->subject('Mail from ' . 'PaymentHub')
            ->markdown('mail.invoice_link')
            ->with([
                'url' => $this->url,
                'logo' => $logo,
                'organization' => $this->organization
            ]);
    }
}
