<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CheckoutEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName, $book, $uuid;

    /**
     * Create a new message instance.
     *
     * @param string $userName
     * @return void
     */
    public function __construct($userName, $book, $uuid)
    {
        $this->userName = $userName;
        $this->book = $book;
        $this->uuid = $uuid;
    }

    /**
     * Sned a message to the book owner notifying them that someone has checked out their book.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.checkout')
                    ->with('owner', $this->userName)
                    ->with('book', $this->book)
                    ->with('uuid', $this->uuid);

    }
}
