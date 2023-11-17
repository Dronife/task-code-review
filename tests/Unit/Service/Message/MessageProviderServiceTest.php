<?php declare(strict_types=1);

namespace App\Tests\Unit\Service\Message;

use App\Constant\NotificationType;
use App\Domain\Message\Hydrator;
use App\Entity\Customer;
use App\Model\Message;
use App\Service\Message\MessageProviderService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MessageProviderServiceTest extends TestCase
{
    private const DATA = ['body' => 'This is test body'];
    private const BAD_DATA = ['body1' => 'Bad data'];
    private const NOTIFICATION_TYPE = NotificationType::TYPE_EMAIL;

    private MockObject $hydrator;
    private MockObject $customer;
    private MessageProviderService $messageProviderService;

    public function setUp(): void
    {
        $this->hydrator = $this->createMock(Hydrator::class);
        $this->customer = $this->createMock(Customer::class);
        $this->messageProviderService = new MessageProviderService($this->hydrator);
    }

    /**
     * @test
     */
    public function shouldReturnModelFromData(): void
    {
        $expectedModel = (new Message())
            ->setBody(self::DATA['body'])
            ->setType(self::NOTIFICATION_TYPE);

        $this->hydrator->expects(self::once())
            ->method('hydrateToModel')
            ->with(self::DATA, $this->customer)
            ->willReturn($expectedModel);

        $model = $this->messageProviderService->getModel(self::DATA, $this->customer);

        self::assertSame($expectedModel, $model);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenGivenBadData(): void
    {
        $this->hydrator->expects(self::once())
            ->method('hydrateToModel')
            ->with(self::BAD_DATA, $this->customer)
            ->willThrowException(new \InvalidArgumentException('The body key is missing in the data array.'));

        $this->expectException(\InvalidArgumentException::class);

        $this->messageProviderService->getModel(self::BAD_DATA, $this->customer);
    }
}
