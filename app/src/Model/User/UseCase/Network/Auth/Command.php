<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Network\Auth;

class Command
{
    /**
     * @var string
     */
    public $network;

    /**
     * @var string
     */
    public $networkId;

    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    public function __construct(string $network, string $networkId)
    {
        $this->network = $network;
        $this->networkId = $networkId;
    }
}