<?php

declare(strict_types=1);

namespace App\Model\Blog\UseCase\Category\Status;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank()
     */
    public $id;

    /**
     * @Assert\NotBlank()
     */
    public $status;

    public function __construct(int $id,int $status)
    {
        $this->id = $id;
        $this->status = $status;
    }

}