<?php declare(strict_types=1);

namespace App\Service\Sender;

use App\Entity\Customer;
use App\Provider\Sender\SenderInterface;
use App\Provider\SenderFactory;

class SenderProviderService
{
    private SenderFactory $senderFactory;

    public function __construct(SenderFactory $senderFactory)
    {
        $this->senderFactory = $senderFactory;
    }

    public function getSender(Customer $customer): SenderInterface
    {
        return $this->senderFactory->getTypeAsStrategy($customer->getNotificationType());
    }
}