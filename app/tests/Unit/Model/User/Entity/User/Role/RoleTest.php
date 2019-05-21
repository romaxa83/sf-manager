<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\Role;

use App\Model\User\Entity\User\Role;
use App\Model\User\Entity\User\User;
use App\Tests\Builder\User\UserBuilder;
use PHPUnit\Framework\TestCase;


class RoleTest extends TestCase
{
    public function testSuccess(): void
    {
        /** @var $user User*/
        $user = (new UserBuilder())->viaEmail()->build();

        $user->changeRole(Role::admin());

        self::assertFalse($user->getRole()->isUser());
        self::assertTrue($user->getRole()->isAdmin());
    }

    public function testAlready(): void
    {
        /** @var $user User*/
        $user = (new UserBuilder())->viaEmail()->build();

        $this->expectExceptionMessage('Role is already same');
        $user->changeRole(Role::user());
    }
}