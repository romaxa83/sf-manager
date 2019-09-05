<?php

declare(strict_types = 1);

namespace App\ReadModel\Blog\Post;

use App\Model\Blog\Entity\Post\Post;
use App\ReadModel\Blog\Post\Filter\Filter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class PostFetcher
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
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(
        Connection $connection,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    )
    {
        $this->connection = $connection;
        $this->repository = $em->getRepository(Post::class);
        $this->em = $em;
        $this->paginator = $paginator;
    }

//    public function all()
//    {
//        $stmt = $this->connection->createQueryBuilder()
//            ->select(
//                'id',
//                'title',
//                'status',
//                'created'
//            )
//            ->from('blog_categories')
//            ->orderBy('name')
//            ->execute();
//
//        return $stmt->fetchAll(FetchMode::ASSOCIATIVE);
//    }

    /**
     * @param Filter $filter
     * @param int $page
     * @param int $size
     * @param string $sort
     * @param string $direction
     * @return PaginationInterface
     */
    public function all(Filter $filter, int $page, int $size, string $sort, string $direction): PaginationInterface
    {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                'p.id',
                'p.title',
                'p.slug',
                'p.created',
                'p.status',
                'p.body'
            )
            ->from('blog_posts', 'p');
        if ($filter->title) {
            $qb->andWhere($qb->expr()->like('p.title', ':title'));
            $qb->setParameter(':title', '%' . $filter->title . '%');
        }
        if ($filter->status) {
            $qb->andWhere('p.status = :status');
            $qb->setParameter(':status', $filter->status);
        }
        if (!\in_array($sort, ['title', 'created', 'status'], true)) {
            throw new \UnexpectedValueException('Cannot sort by ' . $sort);
        }

        $qb->orderBy($sort, $direction === 'desc' ? 'desc' : 'asc');

        return $this->paginator->paginate($qb, $page, $size);
    }

}