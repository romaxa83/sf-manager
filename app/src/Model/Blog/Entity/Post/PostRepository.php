<?php

declare(strict_types=1);

namespace App\Model\Blog\Entity\Post;

use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

class PostRepository
{
    private $em;
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(Post::class);
    }

    public function get($id): Post
    {
        /** @var Post $post */
        if (!$post = $this->repo->find($id)) {
            throw new EntityNotFoundException('Post is not found.');
        }
        return $post;
    }

    public function getAllPost()
    {
        return $this->repo->findAll();
    }

    public function add(Post $post): void
    {
        $this->em->persist($post);
    }

    public function remove(Post $post): void
    {
        $this->em->remove($post);
    }

}