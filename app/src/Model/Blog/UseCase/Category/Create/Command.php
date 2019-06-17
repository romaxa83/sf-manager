<?php

declare(strict_types=1);

namespace App\Model\Blog\UseCase\Category\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min="6")
     */
    public $title;
}