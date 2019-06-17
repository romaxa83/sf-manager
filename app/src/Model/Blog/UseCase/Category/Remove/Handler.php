<?php

declare(strict_types=1);

namespace App\Model\Blog\UseCase\Category\Remove;

use App\Model\Blog\Entity\Category\CategoryRepository;
use App\Model\Flusher;

class Handler
{
    private $categoryRepository;
    private $flusher;

    public function __construct(CategoryRepository $categoryRepository, Flusher $flusher)
    {
        $this->categoryRepository = $categoryRepository;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $category = $this->categoryRepository->get($command->id);

        $this->categoryRepository->remove($category);

        $this->flusher->flush();
    }
}
