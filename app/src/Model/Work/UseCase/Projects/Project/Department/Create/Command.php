<?php
declare(strict_types=1);

namespace App\Model\Work\UseCase\Projects\Project\Department\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank()
     */
    public $project;
    /**
     * @Assert\NotBlank()
     */
    public $name;

    public function __construct(string $project)
    {
        $this->project = $project;
    }
}