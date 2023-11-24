<?php declare(strict_types=1);

namespace App\Provider\Sender;

use App\Model\Message;

class EmailSender implements SenderInterface
{
    public function send(Message $message): void
    {
        print 'EMAIL';
    }
}