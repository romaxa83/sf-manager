<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Reset\Reset;

use App\Model\Flusher;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\PasswordHasher;

class Handler
{

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var PasswordHasher
     */
    private $hasher;
    /**
     * @var Flusher
     */
    private $flusher;

    public function __construct(
        UserRepository $userRepository,
        PasswordHasher $hasher,
        Flusher $flusher
    )
    {
        $this->userRepository = $userRepository;
        $this->hasher = $hasher;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        if(!$user = $this->userRepository->findByResetToken($command->token)){
            throw new \DomainException('Incorrect or confirmed token.');
        }
        /** @var $user User */
        $user->passwordReset(
            new \DateTimeImmutable(),
            $this->hasher->hash($command->password)
        );

        $this->flusher->flush();
    }
}