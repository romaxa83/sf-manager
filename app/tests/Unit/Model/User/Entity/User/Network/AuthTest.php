<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\Network;

use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Network;
use App\Model\User\Entity\User\User;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
   public function testSuccess(): void
   {
       /** @var $user User*/
       $user = User::signUpByNetwork(
           Id::next(),
           new \DateTimeImmutable(),
           $network = 'fb',
           $networkId = '09009090'
       );

       self::assertTrue($user->isActive());
       self::assertCount(1,$networks = $user->getNetworks());
       self::assertInstanceOf(Network::class,$first = reset($networks));
       self::assertEquals($network,$first->getNetwork());
       self::assertEquals($networkId,$first->getNetworkId());

       self::assertTrue($user->getRole()->isUser());
   }
}