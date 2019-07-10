<?php

declare(strict_types=1);

namespace App\Model\Blog\Entity\Category;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="blog_categories")
 */
class Category
{
    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 2;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="title", type="string", length=64)
     */
    private $title;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", nullable=false, length=64, unique=true)
     */
    private $slug;

    /**
     * @var string
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $created;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function create(
        string $title,\DateTimeImmutable $date)
    {
        $this->setTitle($title);
        $this->status = self::STATUS_ACTIVE;
        $this->created = $date;
    }

    public function edit($title)
    {
        $this->title = $title;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this->status === self::STATUS_INACTIVE;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus(integer $status): void
    {
        $this->status = $status;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->created;
    }
}