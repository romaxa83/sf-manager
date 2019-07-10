<?php

declare(strict_types=1);

namespace App\Model\Blog\UseCase\Category\Create;

use App\Model\Blog\Entity\Category\Category;
use App\Model\Blog\Entity\Category\CategoryRepository;
use App\Model\Flusher;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\PasswordHasher;

class Handler
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var Flusher
     */
    private $flusher;

    public function __construct(
        CategoryRepository $categoryRepository,
        Flusher $flusher
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        /** @var $category Category */
        $category = new Category();
        $category->create(
            $command->title,
            new \DateTimeImmutable()
        );
//dd($category);
        $this->categoryRepository->add($category);

        $this->flusher->flush();
    }
}