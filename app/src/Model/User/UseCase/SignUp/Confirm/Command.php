<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Confirm\Request;

class Command
{
    /**
     * @var string
     */
    public $token;
}