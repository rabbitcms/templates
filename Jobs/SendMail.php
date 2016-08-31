<?php

namespace RabbitCMS\Templates\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RabbitCMS\Templates\Entities\Send;

class SendMail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $send;

    public function __construct(Send $send)
    {
        $this->send = $send;
    }

    public function handle(Mailer $mailer)
    {
        $mailer->raw('', function (Message $message) {
            $message->getSwiftMessage()->setBody($this->send->html, 'text/html', 'utf-8');
            $message->getSwiftMessage()->addPart($this->send->plain, 'text/plain', 'utf-8');
            $message->subject($this->send->subject);
            $message->to($this->send->recipient->getRecipientEmail(), $this->send->recipient->getRecipientName());
            if ($callback = $this->send->callback) {
                call_user_func($callback, $message);
            }
        });
        $this->send->update(['sent' => true]);
    }
}