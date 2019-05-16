<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;
use Zend\EventManager\Exception\DomainException;

class User
{
    private const STATUS_WAIT = 'wait';
    private const STATUS_ACTIVE = 'active';
    private const STATUS_NEW = 'new';

	/**
	 * @var Id
	 */
	private $id;

	/**
	 * @var \DateTimeImmutable 
	 */
	private $date;

	/**
	 * @var Email
	 */
	private $email;

	/**
	 * @var string 
	 */
	private $passwordHash;

    /**
     * @var string|null
     */
    private $confirmToken;

    /**
     * @var string
     */
    private $status;

    /**
     * @var Network[]|ArrayCollection
     */
    private $networks;

    /**
     * @var ResetToken|null
     */
    private $resetToken;

	public function __construct(
	    Id $id,
        \DateTimeImmutable $date
    )
	{
		$this->id = $id;
		$this->date = $date;
		$this->status = self::STATUS_NEW;
		$this->networks = new ArrayCollection();
	}

    public function signUpByEmail(
        Email $email,
        string $hash,
        string $token
    ): void
    {
        if(!$this->isNew()){
            throw new \DomainException('User is already signed up.');
        }

        $this->email = $email;
        $this->passwordHash = $hash;
        $this->confirmToken = $token;
        $this->status = self::STATUS_WAIT;
    }

    public function signUpByNetwork(
        string $network,
        string $networkId
    ): void
    {
        if(!$this->isNew()){
            throw new \DomainException('User is already signed up.');
        }
        $this->attachNetwork($network, $networkId);
        $this->status = self::STATUS_ACTIVE;
    }

    private function attachNetwork(string $network, string $networkId): void
    {
        foreach ($this->networks as $existing) {
            if ($existing->isForNetwork($network)) {
                throw new \DomainException('Network is already attached.');
            }
        }
        $this->networks->add(new Network($this, $network, $networkId));
    }

    public function detachNetwork(string $network, string $networkId): void
    {
        foreach ($this->networks as $existing) {
            if ($existing->isFor($network, $networkId)) {
                if (!$this->email && $this->networks->count() === 1) {
                    throw new \DomainException('Unable to detach the last identity.');
                }
                $this->networks->removeElement($existing);
                return;
            }
        }
        throw new \DomainException('Network is not attached.');
    }

	public function confirmSignUp(): void
    {
        if(!$this->isWait()){
            throw new \DomainException('User is already confirmed.');
        }

        $this->status = self::STATUS_ACTIVE;
        $this->confirmToken = null;
    }

    public function requestPasswordReset(ResetToken $token, \DateTimeImmutable $date): void
    {
        if(!$this->isActive()){
            throw new \DomainException('User is not active.');
        }
        if(!$this->email){
            throw new \DomainException('Email is not specified.');
        }
        if($this->resetToken && !$this->resetToken->isExpiredTo($date)){
            throw new \DomainException('Resetting is already requested.');
        }
        $this->resetToken = $token;
    }

    public function passwordReset(\DateTimeImmutable $date, string $hash): void
    {
        if (!$this->resetToken) {
            throw new \DomainException('Resetting is not requested.');
        }
        if ($this->resetToken->isExpiredTo($date)) {
            throw new \DomainException('Reset token is expired.');
        }
        $this->passwordHash = $hash;
        $this->resetToken = null;
    }

    /**
     * @return null|string
     */
    public function getConfirmToken(): ?string
    {
        return $this->confirmToken;
    }

	public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isNew(): bool
    {
        return $this->status === self::STATUS_NEW;
    }

	public function getId(): Id
	{
		return $this->id;
	}

	public function getDate(): \DateTimeImmutable
	{
		return $this->date;
	}

	public function getEmail(): Email
	{
		return $this->email;
	}

	public function getPasswordHash(): string
	{
		return $this->passwordHash;
	}

    public function getResetToken(): ?ResetToken
    {
        return $this->resetToken;
    }

    /**
     * @return Network[]
     */
    public function getNetworks(): array
    {
        return $this->networks->toArray();
    }
}