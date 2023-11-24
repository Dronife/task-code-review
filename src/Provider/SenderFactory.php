<?php declare(strict_types=1);

namespace App\Provider;

use App\Constant\NotificationType;
use App\Provider\Sender\EmailSender;
use App\Provider\Sender\SenderInterface;
use App\Provider\Sender\SmsSender;

class SenderFactory
{
    private EmailSender $emailSender;
    private SmsSender $smsSender;

    public function __construct(EmailSender $emailSender, SmsSender $smsSender)
    {
        $this->emailSender = $emailSender;
        $this->smsSender = $smsSender;
    }

    public function getTypeAsStrategy(string $notificationType): SenderInterface
    {
        if ($notificationType === NotificationType::TYPE_SMS) {
            return $this->smsSender;
        }

        return $this->emailSender;
    }
}