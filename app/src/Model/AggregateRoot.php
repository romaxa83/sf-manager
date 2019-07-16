<?php

declare(strict_types=1);

namespace App\Model;

//интерфейс который извлекает события из сущности
interface AggregateRoot
{
    public function releaseEvents(): array;
}