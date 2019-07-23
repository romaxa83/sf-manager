<?php

declare(strict_types=1);

namespace App\Model\Blog\Entity\Post;

use Webmozart\Assert\Assert;

class Status
{
    public const ACTIVE = 'active';
    public const DRAFT = 'draft';
    public const POSTPONED_PUBLICATION = 'postponed publication';

    private $name;

    public function __construct(string $name)
    {
        Assert::oneOf($name, [
            self::ACTIVE,
            self::DRAFT,
            self::POSTPONED_PUBLICATION
        ]);
        $this->name = $name;
    }

    public static function active(): self
    {
        return new self(self::ACTIVE);
    }

    public static function draft(): self
    {
        return new self(self::DRAFT);
    }

    public function isEqual(self $other): bool
    {
        return $this->getName() === $other->getName();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isActive(): bool
    {
        return $this->name === self::ACTIVE;
    }

    public function isDraft(): bool
    {
        return $this->name === self::DRAFT;
    }

    public function isPostponedPublication(): bool
    {
        return $this->name === self::POSTPONED_PUBLICATION;
    }
}