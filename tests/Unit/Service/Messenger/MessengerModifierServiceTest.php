<?php declare(strict_types=1);

namespace App\Tests\Unit\Service\Messenger;

use App\Model\Message;
use App\Provider\Sender\EmailSender;
use App\Provider\Sender\SmsSender;
use App\Service\Messenger\MessengerService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MessengerModifierServiceTest extends TestCase
{
    private MessengerService $messengerService;
    private MockObject $smsSender;
    private MockObject $emailSender;

    public function setUp(): void
    {
        $this->smsSender = $this->createMock(SmsSender::class);
        $this->emailSender = $this->createMock(EmailSender::class);
        $this->messengerService = new MessengerService();
    }

    /**
     * @test
     */
    public function shouldSendSmsWhenTypeIsSms(): void
    {
        $message = $this->createMock(Message::class);

        $this->smsSender->expects(self::once())
            ->method('send')
            ->with($message);

        $this->messengerService->send($this->smsSender, $message);
    }

    /**
     * @test
     */
    public function shouldSendEmailWhenTypeIsEmail(): void
    {
        $message = $this->createMock(Message::class);

        $this->emailSender->expects(self::once())
            ->method('send')
            ->with($message);

        $this->messengerService->send($this->emailSender, $message);
    }
}
