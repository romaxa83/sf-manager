<?php

declare(strict_types=1);

namespace App\Model\Blog\UseCase\Category\Edit;

use Symfony\Component\Validator\Constraints as Assert;
use App\Model\Blog\Entity\Category\Category;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min="6")
     */
    public $title;


    /**
     * @var integer
     */
    public $id;

    public function __construct(Category $category)
    {
        $this->title = $category->getTitle();
        $this->id = $category->getId();
    }

    public static function fromCategory(Category $category): self
    {
        $command = new self($category);
        $command->title = $category->getTitle();
        $command->id = $category->getId();
        return $command;
    }
}