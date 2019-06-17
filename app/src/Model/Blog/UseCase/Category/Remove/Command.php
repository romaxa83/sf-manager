<?php

declare(strict_types=1);

namespace App\Model\Blog\UseCase\Category\Remove;

class Command
{
    /**
     * @var integer
     */
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
}