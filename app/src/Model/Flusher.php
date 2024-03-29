<?php
declare(strict_types=1);

namespace App\Model;

use Doctrine\ORM\EntityManagerInterface;

class Flusher
{
    private $em;
    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    public function __construct(EntityManagerInterface $em, EventDispatcher $dispatcher)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    public function flush(AggregateRoot ...$roots): void
    {
        $this->em->flush();

        //запускаем все события, после сохранение, если они есть
        foreach ($roots as $root) {
            $this->dispatcher->dispatch($root->releaseEvents());
        }
    }
}