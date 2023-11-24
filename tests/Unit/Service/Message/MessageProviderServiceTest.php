<?php declare(strict_types=1);

namespace App\Tests\Unit\Service\Message;

use App\Domain\Message\Hydrator;
use App\Model\Message;
use App\Service\Message\MessageProviderService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MessageProviderServiceTest extends TestCase
{
    private const DATA = ['body' => 'This is test body'];
    private const BAD_DATA = ['body1' => 'Bad data'];

    private MockObject $hydrator;
    private MessageProviderService $messageProviderService;

    public function setUp(): void
    {
        $this->hydrator = $this->createMock(Hydrator::class);
        $this->messageProviderService = new MessageProviderService($this->hydrator);
    }

    /**
     * @test
     */
    public function shouldReturnModelFromData(): void
    {
        $expectedModel = (new Message())
            ->setBody(self::DATA['body']);

        $this->hydrator->expects(self::once())
            ->method('hydrateFromArrayToModel')
            ->with(self::DATA)
            ->willReturn($expectedModel);

        $model = $this->messageProviderService->hydrateFromArrayToModel(self::DATA);

        self::assertSame($expectedModel, $model);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenGivenBadData(): void
    {
        $this->hydrator->expects(self::once())
            ->method('hydrateFromArrayToModel')
            ->with(self::BAD_DATA)
            ->willThrowException(new \InvalidArgumentException('The body key is missing in the data array.'));

        $this->expectException(\InvalidArgumentException::class);

        $this->messageProviderService->hydrateFromArrayToModel(self::BAD_DATA);
    }
}
