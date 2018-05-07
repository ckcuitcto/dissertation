<?php

namespace App\Mail;

use App\Model\Comment;
use App\Model\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReplyComment extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $content;
    public $comment;

    public function __construct(Comment $comment ,$content)
    {
        $this->comment = $comment;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.reply-comment')->subject("Phản hồi ý kiến: ". $this->comment->title);;
    }
}
