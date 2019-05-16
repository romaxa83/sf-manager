<?php
declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Ramsey\Uuid\Uuid;

class Network
{

    private $id;

    private $user;

    private $network;

    private $networkId;

    /**
     * Network constructor.
     * @param User $user
     * @param string $network
     * @param string $networkId
     * @throws \Exception
     */
    public function __construct(User $user, string $network, string $networkId)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->user = $user;
        $this->network = $network;
        $this->networkId = $networkId;
    }

    public function isFor(string $network, string $networkId): bool
    {
        return $this->network === $network && $this->networkId === $networkId;
    }

    public function isForNetwork(string $network): bool
    {
        return $this->network === $network;
    }

    public function getNetwork(): string
    {
        return $this->network;
    }

    public function getNetworkId(): string
    {
        return $this->networkId;
    }
}