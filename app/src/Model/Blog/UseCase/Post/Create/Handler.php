<?php

declare(strict_types=1);

namespace App\Model\Blog\UseCase\Post\Create;

use App\Model\Blog\Entity\Category\Category;
use App\Model\Blog\Entity\Category\CategoryRepository;
use App\Model\Blog\Entity\Post\Post;
use App\Model\Blog\Entity\Post\PostRepository;
use App\Model\Flusher;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\PasswordHasher;

class Handler
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var Flusher
     */
    private $flusher;

    public function __construct(
        PostRepository $postRepository,
        Flusher $flusher
    )
    {
        $this->postRepository = $postRepository;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        /** @var $post Post */
        $post = new Post();
        $post->create(
            $command->title,
            $command->body,
            new \DateTimeImmutable()
        );

        $this->postRepository->add($post);

        $this->flusher->flush();
    }
}