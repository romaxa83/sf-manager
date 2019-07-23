<?php

declare(strict_types=1);

namespace App\Model\Blog\Entity\Post;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="blog_posts")
 */
class Post
{
    /**
     * @var Id
     * @ORM\Column(type="blog_post_id")
     * @ORM\Id
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
     * @ORM\Column(type="text", nullable=false)
     */
    private $body;

    /**
     * @var Status
     * @ORM\Column(type="blog_post_status", length=16)
     */
    private $status;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $created;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $updated;

    /**
     * @ORM\ManyToOne(targetEntity="App\Model\Blog\Entity\Category\Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Model\User\Entity\User\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $author;

    public function __construct()
    {

    }

}