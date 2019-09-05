<?php

declare(strict_types=1);

namespace App\Model\Blog\UseCase\Post\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min="4")
     */
    public $title;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $body;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $categoryId;
}