<?php

declare(strict_types = 1);

namespace App\ReadModel\User;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;

class UserFetcher
{
    /**
     * работает напрямую с pdo
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * проверяет есть ли пользователь с переданым токеном
     * @param string $token
     * @return bool
     */
    public function existsByResetToken(string $token):bool
    {
        return $this->connection->createQueryBuilder()
            ->select('COUNT (*)')
            ->from('user_users')
            ->where('reset_token_token = :token')
            ->setParameter(':token',$token)
            ->execute()->fetchColumn() > 0;
    }

    public function findForAuthByEmail(string $email): ?AuthView
    {
        //работаем с pdo в dbal
        $stmt = $this->connection->createQueryBuilder()
            ->select('id','email','password_hash','role','status')
            ->from('user_users')
            ->where('email = :email')
            ->setParameter(':email',$email)
            ->execute();

        //данные маппим в класс AuthView
        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT,AuthView::class);
        $result = $stmt->fetch();

        return $result ?: null;
    }
}