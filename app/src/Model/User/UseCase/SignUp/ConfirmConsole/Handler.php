<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\ConfirmConsole;

use App\Model\Flusher;
use App\Model\User\Entity\User\Id;
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

    public function handle(Command $command):void
    {
        $user = $this->userRepository->get(new Id($command->id));

        $user->confirmSignUp();

        $this->flusher->flush();
    }
}