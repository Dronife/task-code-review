<?php declare(strict_types=1);

namespace App\Service\Messenger;

use App\Domain\Core\Sender\SenderInterface;
use App\Model\Message;
use Iterator;

class MessengerModifierService
{
    /**
     * @var Iterator<SenderInterface>
     */
    private iterable $messageSenders;

    /**
     * @param Iterator<SenderInterface> $messageSenders
     */
    public function __construct(iterable $messageSenders)
    {
        $this->messageSenders = $messageSenders;
    }

    /**
     * @throws \Exception
     */
    public function send(Message $message): void
    {
        foreach ($this->messageSenders as $messageSender) {
            if ($messageSender->supports($message)) {
                $messageSender->send($message);

                return;
            }
        }

        throw new \Exception('Was not able to send a message.');
    }
}