<?php declare(strict_types=1);

namespace App\Model;

use App\Constant\NotificationType;

class Message
{
    private string $body;
    private string $type = NotificationType::TYPE_EMAIL;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }
}