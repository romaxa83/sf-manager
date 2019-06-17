<?php

declare(strict_types=1);

namespace App\Model\Blog\Entity\Category;

use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

class CategoryRepository
{
    private $em;
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(Category::class);
    }

    public function get($id): Category
    {
        /** @var Category $category */
        if (!$category = $this->repo->find($id)) {
            throw new EntityNotFoundException('Category is not found.');
        }
        return $category;
    }

    public function getAllCategory()
    {
        return $this->repo->findAll();
    }

    public function add(Category $category): void
    {
        $this->em->persist($category);
    }

    public function remove(Category $category): void
    {
        $this->em->remove($category);
    }

}