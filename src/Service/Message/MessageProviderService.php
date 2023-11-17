<?php declare(strict_types=1);

namespace App\Service\Message;

use App\Domain\Message\Hydrator;
use App\Entity\Customer;
use App\Model\Message;

class MessageProviderService
{
    private Hydrator $hydrator;

    public function __construct(Hydrator $hydrator)
    {
        $this->hydrator = $hydrator;
    }

    /**
     * @param array $data = [
     *      'body' => '',
     * ]
     *
     * @throws \InvalidArgumentException
     */
    public function getModel(array $data, Customer $customer): Message
    {
        return $this->hydrator->hydrateToModel($data, $customer);
    }
}