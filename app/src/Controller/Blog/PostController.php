<?php
declare(strict_types=1);

namespace App\Controller\Blog;

use App\Controller\ErrorHandler;
use App\Model\Blog\UseCase\Post;
use App\ReadModel\Blog\Post\PostFetcher;
use App\ReadModel\Blog\Post\Filter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    private const PER_PAGE = 20;

    private $errors;

    public function __construct(ErrorHandler $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @Route("/blog/posts", name="blog.posts")
     * @return Response
     */
    public function index(
        Request $request,
        PostFetcher $postFetcher
    ): Response
    {
        $filter = new Filter\Filter();

        $form = $this->createForm(Filter\Form::class, $filter);
        $form->handleRequest($request);

        $pagination = $postFetcher->all(
            $filter,
            $request->query->getInt('page', 1),
            self::PER_PAGE,
            $request->query->get('sort', 'title'),
            $request->query->get('direction', 'asc')
        );

        return $this->render('app/blog/post/index.html.twig',[
            'pagination' => $pagination,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/blog/post/create", name="blog.post.create")
     * @param Request $request
     * @param Post\Create\Handler $handler
     * @return Response
     * @throws \Exception
     */
    public function create(Request $request, Post\Create\Handler $handler): Response
    {
        $command = new Post\Create\Command();
        //создаем форму и оборачиваем в класс command
//        $form = $this->createForm(Post\Create\Form::class,$command);
        $form = $this->createForm(Post\Create\Form::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            try{
                $handler->handle($command);
                $this->addFlash('success','Post created successfully.');

                return $this->redirectToRoute('blog.posts');
            } catch (\DomainException $e){
                $this->errors->handle($e);
                $this->addFlash('error',$e->getMessage());
            }
        }
        return $this->render('app/blog/post/create.html.twig',[
            'form' => $form->createView()
        ]);
    }
}