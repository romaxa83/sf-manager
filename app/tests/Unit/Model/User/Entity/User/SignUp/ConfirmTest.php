<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\SignUp;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;
use PHPUnit\Framework\TestCase;

class ConfirmTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = $this->buildSignUpUser();

        $user->confirmSignUp();

        self::assertFalse($user->isWait());
        self::assertTrue($user->isActive());

        self::assertNull($user->getConfirmToken());
    }

    public function testAlready(): void
    {
        $user = $this->buildSignUpUser();

        $user->confirmSignUp();
        $this->expectExceptionMessage('User is already confirmed');
        $user->confirmSignUp();
    }

    private function buildSignUpUser(): User
    {
        return new User(
            $id = Id::next(),
            $date = new \DateTimeImmutable(),
            $email = new Email('test@test.com'),
            $hash = 'hash',
            $token = 'token'
        );
    }
}