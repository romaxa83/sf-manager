<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\ConfirmConsole;

class Command
{
    /**
     * @var string
     */
    public $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}