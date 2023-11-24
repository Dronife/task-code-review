<?php declare(strict_types=1);

namespace App\Tests\Unit\Service\Notification;

use App\Entity\Customer;
use App\Model\Message;
use App\Provider\Sender\SenderInterface;
use App\Service\Customer\CustomerProviderService;
use App\Service\Message\MessageProviderService;
use App\Service\Messenger\MessengerService;
use App\Service\Notification\NotificationOrchestratorService;
use App\Service\Sender\SenderProviderService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class NotificationOrchestratorServiceTest extends TestCase
{
    private NotificationOrchestratorService $notificationOrchestratorService;
    private MockObject $customerProviderService;
    private MockObject $messageProviderService;
    private MockObject $messengerModifierService;
    private MockObject $senderProviderService;

    private const DATA = ['body' => 'This is test body'];
    private const BAD_DATA = ['body1' => 'no data'];
    private const CODE = 'testCode';
    private const CODE_DOES_NOT_EXIST = 'codeDoesNotExist';

    public function setUp(): void
    {
        $this->customerProviderService = $this->createMock(CustomerProviderService::class);
        $this->messageProviderService = $this->createMock(MessageProviderService::class);
        $this->messengerModifierService = $this->createMock(MessengerService::class);
        $this->senderProviderService = $this->createMock(SenderProviderService::class);

        $this->notificationOrchestratorService = new NotificationOrchestratorService(
            $this->customerProviderService,
            $this->messageProviderService,
            $this->messengerModifierService,
            $this->senderProviderService
        );
    }

    /**
     * @test
     */
    public function shouldSendMessageSuccessfully(): void
    {
        $customer = $this->createMock(Customer::class);
        $message = $this->createMock(Message::class);
        $sender = $this->createMock(SenderInterface::class);

        $this->customerProviderService->expects(self::once())
            ->method('getOneCustomerByCode')
            ->with(self::CODE)
            ->willReturn($customer);

        $this->messageProviderService->expects(self::once())
            ->method('hydrateFromArrayToModel')
            ->with(self::DATA)
            ->willReturn($message);

        $this->senderProviderService->expects(self::once())
            ->method('getSender')
            ->with($customer)
            ->willReturn($sender);

        $this->messengerModifierService->expects(self::once())
            ->method('send')
            ->with($sender, $message);

        $this->notificationOrchestratorService->send(self::DATA, self::CODE);
    }

    /**
     * @test
     */
    public function shouldGetExceptionIfCustomerWithCodeDoesNotExist(): void
    {
        $this->customerProviderService->expects(self::once())
            ->method('getOneCustomerByCode')
            ->with(self::CODE_DOES_NOT_EXIST)
            ->willThrowException(new \Exception('Customer not found.'));

        $this->expectException(\Exception::class);

        $this->notificationOrchestratorService->send(self::DATA, self::CODE_DOES_NOT_EXIST);
    }

    /**
     * @test
     */
    public function shouldGetExceptionIfDataIsBad(): void
    {
        $customer = $this->createMock(Customer::class);

        $this->customerProviderService->expects(self::once())
            ->method('getOneCustomerByCode')
            ->with(self::CODE)
            ->willReturn($customer);

        $this->messageProviderService->expects(self::once())
            ->method('hydrateFromArrayToModel')
            ->with(self::DATA)
            ->willThrowException(new \InvalidArgumentException('The body key is missing in the data array.'));

        $this->expectException(\InvalidArgumentException::class);

        $this->notificationOrchestratorService->send(self::DATA, self::CODE);
    }
}
