<?php

namespace App\Requests\CustomerController;

use App\Requests\BaseRequest;
use Symfony\Component\Validator\Constraints as Assert;

class NotificationRequest extends BaseRequest
{
    /**
     * @Assert\NotBlank
     */
    public string $body;
}