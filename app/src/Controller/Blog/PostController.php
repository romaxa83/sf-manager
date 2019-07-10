<?php
declare(strict_types=1);

namespace App\Controller\Blog;

use App\Controller\ErrorHandler;
use App\Model\Blog\UseCase\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\Blog\Entity\Category\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/blog/post", name="blog.post")
 */
class PostController extends AbstractController
{
    private $errors;

    public function __construct(ErrorHandler $errors)
    {
        $this->errors = $errors;
    }
}