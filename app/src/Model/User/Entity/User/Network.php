<?php
declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Ramsey\Uuid\Uuid;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_user_networks", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"network", "network_id"})
 * })
 */
class Network
{
    /**
     * @var string
     * @ORM\Column(type="guid")
     * @ORM\Id
     */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="networks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $network;

    /**
     * @var string
     * @ORM\Column(name="network_id", type="string", length=32, nullable=true)
     */
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