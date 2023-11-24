<?php declare(strict_types=1);

namespace App\Model;

class Message
{
    private string $body;

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