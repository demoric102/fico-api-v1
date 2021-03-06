<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendDoc extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
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
        return $this->from('monitor@crccreditbureau.com')->view('auth.send-doc')->attach('documentation/fico-api-documentation.pdf', [
            'as' => 'fico-api-documentation.pdf',
            'mime' => 'application/pdf',
        ]);;
    }
}
