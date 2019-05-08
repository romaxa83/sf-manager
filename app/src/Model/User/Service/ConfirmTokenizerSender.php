<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\User\Entity\User\Email;

interface ConfirmTokenizerSender
{
    public function send(Email $email, string $token): void;
}