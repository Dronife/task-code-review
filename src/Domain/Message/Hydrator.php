<?php declare(strict_types=1);

namespace App\Domain\Message;

use App\Entity\Customer;
use App\Model\Message;

class Hydrator
{
    /**
     * @param array $data = [
     *      'body' => '',
     * ]
     *
     * @throws \InvalidArgumentException
     */
    public function hydrateToModel(array $data, Customer $customer): Message
    {
        if (!array_key_exists('body', $data)) {
            throw new \InvalidArgumentException('The body key is missing in the data array.');
        }

        return (new Message())
            ->setType($customer->getNotificationType())
            ->setBody($data['body']);
    }
}