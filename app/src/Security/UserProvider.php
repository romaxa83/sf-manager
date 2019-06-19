<?php
declare(strict_types=1);

namespace App\Security;

use App\ReadModel\User\AuthView;
use App\ReadModel\User\UserFetcher;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

//реализуем свой провайдер так как мы не трогамем doctrine
class UserProvider implements UserProviderInterface
{
    /**
     * @var UserFetcher
     */
    private $users;

    public function __construct(UserFetcher $users)
    {
        $this->users = $users;
    }

    public function loadUserByUsername($username)
    {
        $user = $this->loadUser($username);
        return self::identityByUser($user, $username);
    }

    /**
     * вызываеться кажды раз когда заходишь на страницу,нужен для
     * обновление пользователя если он изменил свои данные(имя,пароль,...),
     * в данном методе возвращаем ту же identity,но можно сгонять в базу и обновить пользователя
     * @param UserInterface $identity
     * @return UserInterface
     */
    public function refreshUser(UserInterface $identity): UserInterface
    {
        if(!$identity instanceof UserIdentity){
            throw new UnsupportedUserException('Invalid user class ' . \get_class($identity));
        }

        $user = $this->loadUser($identity->getUsername());
        return self::identityByUser($user, $identity->getUsername());
    }

    public function supportsClass($class): bool
    {
        return $class instanceof UserIdentity;
    }

    private function loadUser($username): AuthView
    {
        $chunk = explode(':', $username);

        //если username после регистрации через соц. сеть
        if(\count($chunk) === 2 && $user = $this->users->findForAuthByNetwork($chunk[0],$chunk[1])){
            return $user;
        }

        if ($user = $this->users->findForAuthByEmail($username)) {
            return $user;
        }
        throw new UsernameNotFoundException('');
    }

    private static function identityByUser(AuthView $user, string $username): UserIdentity
    {
        return new UserIdentity(
            $user->id,
            $username,
            $user->password_hash ?: '',
            $user->role,
            $user->status
        );
    }

}