<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Network\Auth;

use App\Model\Flusher;
use App\Model\User\Entity\User\Id;
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

    public function __construct(
        UserRepository $userRepository,
        Flusher $flusher
    )
    {
        $this->userRepository = $userRepository;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     * @throws \Exception
     */
    public function handle(Command $command):void
    {
        if($this->userRepository->hasByNetworkId($command->network,$command->networkId)){
            throw new \DomainException('User already exists.');
        }

        $user = User::signUpByNetwork(
            Id::next(),
            new \DateTimeImmutable(),
            $command->network,
            $command->networkId
        );

        $this->userRepository->add($user);

        $this->flusher->flush();
    }
}