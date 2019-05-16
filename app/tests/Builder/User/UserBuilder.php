<?php

declare(strict_types=1);

namespace App\Tests\Builder\User;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\Id;

class UserBuilder
{
    private $id;
    private $date;

    private $email;
    private $hash;
    private $token;
    private $confirmed;

    private $network;
    private $networkId;

    public function __construct()
    {
        $this->id = Id::next();
        $this->date = new \DateTimeImmutable();
    }

    public function viaEmail(Email $email = null, string $hash = null, string $token = null): self
    {
        $clone = clone $this;
        $clone->email = $email ?? new Email('mail@app.test');
        $clone->hash = $hash ?? 'hash';
        $clone->token = $token ?? 'token';
        return $clone;
    }

    public function confirmed(): self
    {
        $clone = clone $this;
        $clone->confirmed = true;
        return $clone;
    }

    public function viaNetwork(string $network = null, string $networkId = null): self
    {
        $clone = clone $this;
        $clone->network = $network ?? 'vk';
        $clone->networkId = $networkId ?? '0001';
        return $clone;
    }

    public function build(): User
    {
        if ($this->email) {
            $user = User::signUpByEmail(
                $this->id,
                $this->date,
                $this->email,
                $this->hash,
                $this->token
            );
            if($this->confirmed){
                $user->confirmSignUp();
            }
        }
        if ($this->network) {
            $user = User::signUpByNetwork(
                $this->id,
                $this->date,
                $this->network,
                $this->networkId
            );
        }

        return $user;
    }
}