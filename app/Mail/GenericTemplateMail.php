<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GenericTemplateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectLine;
    public $content;
    /**
     * Create a new message instance.
     */
    public function __construct($subjectLine, $content)
    {
        $this->subjectLine = $subjectLine;
        $this->content = $content;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->subject($this->subjectLine)
                    ->html($this->content); // Send raw HTML from DB
    }
}
