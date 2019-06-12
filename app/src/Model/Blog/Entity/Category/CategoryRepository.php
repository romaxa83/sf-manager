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

}