<?php declare(strict_types=1);

namespace App\Constant;

class NotificationType
{
    public const TYPE_SMS = 'sms';
    public const TYPE_EMAIL = 'email';

    public const TYPES = [
        self::TYPE_EMAIL,
        self::TYPE_SMS,
    ];
}