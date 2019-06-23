<?php

namespace App\Command\User;

use App\ReadModel\User\UserFetcher;
use Symfony\Component\Console\Command\Command;
use App\Model\User\UseCase\SignUp\ConfirmConsole;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Form\Exception\LogicException;

//консольная команда для потверждения пользователя по email
class ConfirmCommand extends Command
{
    /**
     * @var UserFetcher
     */
    private $userFetcher;
    /**
     * @var ConfirmConsole\Handler
     */
    private $handler;

    public function __construct(UserFetcher $userFetcher, ConfirmConsole\Handler $handler)
    {
        $this->userFetcher = $userFetcher;
        $this->handler = $handler;
        parent::__construct();
    }

    /**
     * название и описание консольной команды
     */
    protected function configure(): void
    {
        $this->setName('user:confirm')->setDescription('Confirm signed up user');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $email = $helper->ask($input, $output, new Question('Email: '));

        if(!$user = $this->userFetcher->findByEmail($email)){
            throw  new LogicException('User is not found.');
        }

        $command = new ConfirmConsole\Command($user->id);
        $this->handler->handle($command);

        $output->writeln('<info>Done</info>');
    }
}