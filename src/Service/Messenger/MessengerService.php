<?php declare(strict_types=1);

namespace App\Service\Messenger;

use App\Model\Message;
use App\Provider\Sender\SenderInterface;

class MessengerService
{
     /**
     * @throws \Exception
     */
    public function send(SenderInterface $sender,Message $message): void
    {
        try {
            $sender->send($message);

            return;
        }catch(\Exception $e) {
            throw new \Exception('Was not able to send a message.');
        }
    }
}