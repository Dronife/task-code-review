<?php declare(strict_types=1);

namespace App\Tests\Unit\Domain\Message;

use App\Domain\Message\Hydrator;
use App\Model\Message;
use PHPUnit\Framework\TestCase;

class HydratorTest extends TestCase
{
    private const DATA = ['body' => 'This is test body'];
    private const BAD_DATA = ['body1' => 'Bad data'];

    private Hydrator $hydrator;

    public function setUp(): void
    {
        $this->hydrator = new Hydrator();
    }

    /**
     * @test
     */
    public function shouldReturnModel():void
    {
        $expectedModel = (new Message())->setBody(self::DATA['body']);

        $model = $this->hydrator->hydrateFromArrayToModel(self::DATA);

        self::assertEquals($expectedModel, $model);
    }

    /**
     * @test
     */
    public function shouldThrowErrorWithInvalidData(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The body key is missing in the data array.');

        $this->hydrator->hydrateFromArrayToModel(self::BAD_DATA);
    }
}
