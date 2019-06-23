<?php

declare(strict_types = 1);

namespace App\ReadModel\User;

use App\Model\User\Entity\User\User;
use App\ReadModel\NotFoundException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\EntityManagerInterface;

class UserFetcher
{
    /**
     * работает напрямую с pdo
     * @var Connection
     */
    private $connection;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        Connection $connection,
        EntityManagerInterface $em
    )
    {
        $this->connection = $connection;
        $this->repository = $em->getRepository(User::class);
        $this->em = $em;
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
            ->select(
                'id',
                'email',
                'password_hash',
                'TRIM(CONCAT(name_first, \' \', name_last)) AS name',
                'role',
                'status'
            )
            ->from('user_users')
            ->where('email = :email')
            ->setParameter(':email',$email)
            ->execute();

        //данные маппим в класс AuthView
        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT,AuthView::class);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function findForAuthByNetwork(string $network, string $identity): ?AuthView
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'u.id',
                'u.email',
                'u.password_hash',
                'TRIM(CONCAT(u.name_first, \' \', u.name_last)) AS name',
                'u.role',
                'u.status'
            )
            ->from('user_users', 'u')
            ->innerJoin('u','user_user_networks','n','n.user_id = u.id')
            ->where('n.network = :network AND n.network_id =:identity')
            ->setParameter(':network',$network)
            ->setParameter(':identity',$identity)
            ->execute();

        //данные маппим в класс AuthView
        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT,AuthView::class);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function findByEmail(string $email): ?ShortView
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select('id','email','role','status')
            ->from('user_users')
            ->where('email = :email')
            ->setParameter(':email', $email)
            ->execute();

        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, ShortView::class);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    /**
     * @param string $id
     * @return User
     */
    public function get(string $id): User
    {
        if (!$user = $this->repository->find($id)) {
            throw new NotFoundException('User is not found');
        }

        return $user;
    }

    public function findBySignUpConfirmToken(string $token): ?ShortView
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select('id','email','role','status')
            ->from('user_users')
            ->where('confirm_token = :token')
            ->setParameter(':token', $token)
            ->execute();

        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, ShortView::class);
        $result = $stmt->fetch();

        return $result ?: null;
    }
}