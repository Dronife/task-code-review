<?php declare(strict_types=1);

namespace App\Domain\Message;

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
    public function hydrateFromArrayToModel(array $data): Message {
        if (!isset($data['body'])) {
            throw new \InvalidArgumentException('The body key is missing in the data array.');
        }
        return (new Message())->setBody($data['body']);
    }
}