<?php

declare(strict_types=1);

namespace App\Model\Blog\UseCase\Category\Status;

use App\Model\Blog\Entity\Category\CategoryRepository;
use App\Model\Flusher;

class Handler
{
    private $flusher;
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository, Flusher $flusher)
    {
        $this->flusher = $flusher;
        $this->categoryRepository = $categoryRepository;
    }

    public function handle(Command $command): void
    {

        $category = $this->categoryRepository->get($command->id);
        $category->changeStatus($command->status);

        $this->flusher->flush();
    }
}