<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Network\Detach;

class Command
{
    /**
     * @var string
     */
    public $user;

    /**
     * @var string
     */
    public $network;

    /**
     * @var string
     */
    public $networkId;

    public function __construct(string $user, string $network, string $networkId)
    {
        $this->user = $user;
        $this->network = $network;
        $this->networkId = $networkId;
    }
}