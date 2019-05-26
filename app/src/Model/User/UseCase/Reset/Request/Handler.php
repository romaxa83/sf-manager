<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Reset\Request;

use App\Model\Flusher;
use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\ResetTokenizer;
use App\Model\User\Service\ResetTokenSender;

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
    /**
     * @var ResetTokenSender
     */
    private $sender;
    /**
     * @var ResetTokenizer
     */
    private $tokenizer;

    public function __construct(
        UserRepository $userRepository,
        Flusher $flusher,
        ResetTokenSender $sender,
        ResetTokenizer $tokenizer
    )
    {
        $this->userRepository = $userRepository;
        $this->flusher = $flusher;
        $this->sender = $sender;
        $this->tokenizer = $tokenizer;
    }

    public function handler(Command $command): void
    {
        /** @var $user User */
        $user = $this->userRepository->getByEmail(new Email($command->email));

        $user->requestPasswordReset(
            $this->tokenizer->generate(),
            new \DateTimeImmutable()
        );

        $this->flusher->flush();

        $this->sender->send($user->getEmail(),$user->getResetToken());
    }
}