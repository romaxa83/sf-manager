<?php

declare(strict_types=1);

namespace App\Container\Model\User\Service;

use App\Model\User\Service\ResetTokenizer;

//фабрика для создания токена для зброса пароля
class ResetTokenizerFactory
{
    /**
     * @param string $interval
     * @return ResetTokenizer
     */
    public function create(string $interval): ResetTokenizer
    {
        return new ResetTokenizer(new \DateInterval($interval));
    }
}