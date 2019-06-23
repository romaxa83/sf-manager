<?php

declare(strict_types = 1);

namespace App\Security;

use App\Model\User\Entity\User\User;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserIdentity implements UserInterface,EquatableInterface
{
    private $id;
    private $username;
    private $password;
    private $name;
    private $role;
    private $status;

    /**
     * UserIdentity constructor.
     * @param $id
     * @param $username
     * @param $password
     * @param $name
     * @param $role
     * @param $status
     */
    public function __construct(
        $id,
        $username,
        $password,
        $name,
        $role,
        $status
    )
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->name = $name;
        $this->role = $role;
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    public function getFullName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return [$this->role];
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials()
    {

    }

    public function isActive(): bool
    {
        return $this->status === User::STATUS_ACTIVE;
    }


    /**
     * проверка по полям пользователя,
     * перед обновлением identity
     * @param UserInterface $user
     * @return bool
     */
    public function isEqualTo(UserInterface $user): bool
    {
        if (!$user instanceof self) {
            return false;
        }
        return
            $this->id === $user->id &&
            $this->password === $user->password &&
            $this->role === $user->role &&
            $this->status === $user->status;
    }
}