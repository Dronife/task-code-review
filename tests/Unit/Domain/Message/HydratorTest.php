<?php declare(strict_types=1);

namespace App\Tests\Unit\Domain\Message;

use App\Constant\NotificationType;
use App\Domain\Message\Hydrator;
use App\Entity\Customer;
use App\Model\Message;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class HydratorTest extends TestCase
{
    private const DATA = ['body' => 'This is test body'];
    private const BAD_DATA = ['body1' => 'Bad data'];
    private const NOTIFICATION_TYPE = NotificationType::TYPE_EMAIL;

    private MockObject $customer;
    private Hydrator $hydrator;

    public function setUp(): void
    {
        $this->customer = $this->createMock(Customer::class);
        $this->hydrator = new Hydrator();
    }

    /**
     * @test
     */
    public function shouldReturnModel():void
    {
        $expectedModel = (new Message())
            ->setType(self::NOTIFICATION_TYPE)
            ->setBody(self::DATA['body']);

        $this->customer->expects(self::once())->method('getNotificationType')->willReturn(self::NOTIFICATION_TYPE);

        $model = $this->hydrator->hydrateToModel(self::DATA, $this->customer);

        self::assertEquals($expectedModel, $model);
    }

    /**
     * @test
     */
    public function shouldThrowErrorWithInvalidData(): void
    {
        $this->customer->expects(self::any())
            ->method('getNotificationType')
            ->willReturn(self::NOTIFICATION_TYPE);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The body key is missing in the data array.');

        $this->hydrator->hydrateToModel(self::BAD_DATA, $this->customer);
    }
}
