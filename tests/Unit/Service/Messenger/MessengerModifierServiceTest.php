<?php declare(strict_types=1);

namespace App\Tests\Unit\Service\Messenger;

use App\Domain\Core\Sender\EmailSender;
use App\Domain\Core\Sender\SmsSender;
use App\Model\Message;
use App\Service\Messenger\MessengerModifierService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MessengerModifierServiceTest extends TestCase
{
    private MessengerModifierService $messengerModifierService;
    private MockObject $smsSender;
    private MockObject $emailSender;

    public function setUp(): void
    {
        $this->smsSender = $this->createMock(SmsSender::class);
        $this->emailSender = $this->createMock(EmailSender::class);
        $messageSenders = [
            $this->smsSender,
            $this->emailSender,
        ];

        $this->messengerModifierService = new MessengerModifierService($messageSenders);
    }

    /**
     * @test
     */
    public function shouldSendSmsWhenTypeIsSms(): void
    {
        $message = $this->createMock(Message::class);

        $this->smsSender->expects($this->once())
            ->method('supports')
            ->with($message)
            ->willReturn(true);

        $this->smsSender->expects($this->once())
            ->method('send')
            ->with($message);

        $this->emailSender->expects($this->never())
            ->method('supports');

        $this->messengerModifierService->send($message);
    }

    /**
     * @test
     */
    public function shouldSendEmailWhenTypeIsEmail(): void
    {
        $message = $this->createMock(Message::class);

        $this->smsSender->expects($this->once())
            ->method('supports')
            ->with($message)
            ->willReturn(false);

        $this->emailSender->expects($this->once())
            ->method('supports')
            ->with($message)
            ->willReturn(true);

        $this->emailSender->expects($this->once())
            ->method('send')
            ->with($message);

        $this->messengerModifierService->send($message);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenNoSendersSupportMessage(): void
    {
        $message = $this->createMock(Message::class);

        $this->smsSender->expects($this->once())
            ->method('supports')
            ->with($message)
            ->willReturn(false);

        $this->emailSender->expects($this->once())
            ->method('supports')
            ->with($message)
            ->willReturn(false);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Was not able to send a message.');

        $this->messengerModifierService->send($message);
    }
}
