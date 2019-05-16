<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Role;

use App\Model\Flusher;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Role;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserRepository;

class Handler
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var Flusher
     */
    private $flusher;

    public function __construct(UserRepository $userRepository, Flusher $flusher)
    {
        $this->userRepository = $userRepository;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        /** @var $user User*/
        $user = $this->userRepository->get(new Id($command->id));

        $user->changeRole(new Role($command->role));

        $this->flusher->flush();
    }
}