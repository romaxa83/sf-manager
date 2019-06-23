<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Request;

use App\Model\Flusher;
use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Name;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\ConfirmTokenizer;
use App\Model\User\Service\ConfirmTokenizerSender;
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
    private $passwordHasher;
    /**
     * @var Flusher
     */
    private $flusher;
    /**
     * @var ConfirmTokenizer
     */
    private $confirmTokenizer;
    /**
     * @var ConfirmTokenizerSender
     */
    private $confirmTokenizerSender;

    public function __construct(
	    UserRepository $userRepository,
        PasswordHasher $passwordHasher,
        ConfirmTokenizer $confirmTokenizer,
        ConfirmTokenizerSender $confirmTokenizerSender,
        Flusher $flusher
    )
	{
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->flusher = $flusher;
        $this->confirmTokenizer = $confirmTokenizer;
        $this->confirmTokenizerSender = $confirmTokenizerSender;
    }

    /**
     * @param Command $command
     * @throws \Exception
     */
    public function handle(Command $command):void
	{
		$email = new Email($command->email);

		if($this->userRepository->hasByEmail($email)){
			throw new \DomainException('User already exists.');
		}

		$user = User::signUpByEmail(
            Id::next(),
            new \DateTimeImmutable(),
		    new Name(
		        $command->firstName,
		        $command->lastName
            ),
		    $email,
            $this->passwordHasher->hash($command->password),
            $token = $this->confirmTokenizer->generate()
        );

		$this->userRepository->add($user);

		$this->confirmTokenizerSender->send($email,$token);

		$this->flusher->flush();
	}
}