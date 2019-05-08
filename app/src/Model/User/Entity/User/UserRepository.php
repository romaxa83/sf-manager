<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\ORM\EntityManagerInterface;

class UserRepository
{
    private $em;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(User::class);
    }

    /**
     * @param string $token
     * @return User|object|null
     */
    public function findByConfirmToken(string $token): ?User
    {
        return $this->repo->findOneBy(['confirmToken' => $token]);
    }

    public function hasByEmail(Email $email): bool
    {
        return $this->repo->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->andWhere('t.email = :email')
                ->setParameter(':email', $email->getValue())
                ->getQuery()->getSingleScalarResult() > 0;
    }

    public function add(User $user): void
    {
        $this->em->persist($user);
    }
}