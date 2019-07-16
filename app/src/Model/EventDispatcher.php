<?php

declare(strict_types=1);

namespace App\Model;

//интерфейс для диспетчера событий
interface EventDispatcher
{
    public function dispatch(array $events): void;
}