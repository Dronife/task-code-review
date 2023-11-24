<?php declare(strict_types=1);

namespace App\Service\Customer;

use App\Domain\Customer\Provider;
use App\Entity\Customer;

class CustomerProviderService
{
    private Provider $provider;

    public function __construct(Provider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @throws \Exception
     */
    public function getOneCustomerByCode(string $code): Customer
    {
        $customer = $this->provider->getOneByCode($code);
        if ($customer === null) {
            throw new \Exception('Customer not found.');
        }

        return $customer;
    }
}