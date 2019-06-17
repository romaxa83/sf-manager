<?php

declare(strict_types=1);

namespace App\Model\Blog\UseCase\Category\Edit;


use App\Model\Blog\Entity\Category\Category;
use App\Model\Blog\Entity\Category\CategoryRepository;
use App\Model\Flusher;

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
        $category = $this->categoryRepository->get($command->id);
        $category->edit(
            $command->title
        );

//        $this->categoryRepository->add($category);
        $this->flusher->flush();
    }
}