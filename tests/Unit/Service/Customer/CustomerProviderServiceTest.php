<?php declare(strict_types=1);

namespace App\Tests\Unit\Service\Customer;

use App\Domain\Customer\Provider;
use App\Entity\Customer;
use App\Service\Customer\CustomerProviderService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CustomerProviderServiceTest extends TestCase
{
    private MockObject $provider;
    private MockObject $customer;
    private CustomerProviderService $customerProviderService;

    public function setUp(): void
    {
        $this->provider = $this->createMock(Provider::class);
        $this->customer = $this->createMock(Customer::class);
        $this->customerProviderService = new CustomerProviderService($this->provider);
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function shouldReturnCustomer(): void
    {
        $this->provider->expects(self::once())->method('getOneByCode')->willReturn($this->customer);

        $customer = $this->customerProviderService->getOneCustomerByCode('test');

        self::assertSame($this->customer, $customer);
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function shouldThrowExceptionWhenCustomerNull(): void
    {
        $this->provider->expects(self::once())->method('getOneByCode')->willReturn(null);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Customer not found.');

        $this->customerProviderService->getOneCustomerByCode('test');
    }
}
