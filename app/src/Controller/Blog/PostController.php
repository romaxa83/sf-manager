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
 * @Route("/blog/posts", name="blog.posts")
 */
class PostController extends AbstractController
{
    private const PER_PAGE = 20;

    private $errors;

    public function __construct(ErrorHandler $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @Route("", name="")
     * @return Response
     */
    public function index(
        Request $request
//        CategoryFetcher $categoryFetcher
    ): Response
    {
//        $filter = new Filter\Filter();

//        $form = $this->createForm(Filter\Form::class, $filter);
//        $form->handleRequest($request);

//        $pagination = $categoryFetcher->all(
//            $filter,
//            $request->query->getInt('page', 1),
//            self::PER_PAGE,
//            $request->query->get('sort', 'title'),
//            $request->query->get('direction', 'asc')
//        );

        return $this->render('app/blog/post/index.html.twig');

//        return $this->render('app/blog/category/index.html.twig',[
//            'pagination' => $pagination,
//            'form' => $form->createView(),
//        ]);
    }
}