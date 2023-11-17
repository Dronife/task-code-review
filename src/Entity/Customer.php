<?php declare(strict_types=1);

namespace App\Entity;

use App\Constant\NotificationType;
use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 */
class Customer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private string $code;

    /**
     * @ORM\Column(type="string", length=32)
     *
     * @Assert\Choice(choices=NotificationType::TYPES, message="Invalid notification type")
     */
    private string $notificationType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getNotificationType(): string
    {
        return $this->notificationType;
    }

    public function setNotificationType(string $notificationType): self
    {
        if (!in_array($notificationType, NotificationType::TYPES)) {
            throw new \InvalidArgumentException("Invalid notification type");
        }

        $this->notificationType = $notificationType;

        return $this;
    }
}
