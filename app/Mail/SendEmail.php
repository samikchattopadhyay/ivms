<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = array();
        
        if (!isset($this->data['address'])) {
            $address['email'] = env('MAIL_FROM_ADDRESS');
            $address['name'] = env('MAIL_FROM_NAME');
        } else {
            $address = $this->data['address'];
        }
        
        return $this->view('emails.default')
        ->from($address['email'], $address['name'])
        ->replyTo($address['email'], $address['name'])
        ->subject($this->data['subject'])
        ->with([ 'body' => $this->data['message'] ]);
    }
}
