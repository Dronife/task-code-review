<?php declare(strict_types=1);

namespace App\Domain\Customer;

use App\Entity\Customer;
use App\Repository\CustomerRepository;

class Provider
{
    private CustomerRepository $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function getOneByCode(string $code): ?Customer
    {
        return $this->customerRepository->findOneBy(['code' => $code]);
    }
}