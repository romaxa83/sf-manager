<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Confirm;


use App\Model\Flusher;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\UseCase\Confirm\Request\Command;

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

    public function handle(Command $command):void
    {
        if(!$user = $this->userRepository->findByConfirmToken($command->token)){
            throw new \DomainException('Incorrect or confirmed token.');
        }

        $user->confirmSignUp();

        $this->flusher->flush();
    }
}