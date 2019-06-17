<?php
declare(strict_types=1);

namespace App\Controller\Blog;

use Psr\Log\LoggerInterface;
use App\Model\Blog\UseCase\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\Blog\Entity\Category\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/blog/category", name="blog.category.index")
     * @return Response
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('app/blog/category/index.html.twig',[
            'categories' => $categoryRepository->getAllCategory()
        ]);
    }

    /**
     * @Route("/blog/category/create", name="blog.category.create")
     * @param Request $request
     * @param Category\Create\Handler $handler
     * @return Response
     * @throws \Exception
     */
    public function create(Request $request, Category\Create\Handler $handler): Response
    {
        $command = new Category\Create\Command();
        //создаем форму и оборачиваем в класс command
        $form = $this->createForm(Category\Create\Form::class,$command);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            try{
                $handler->handle($command);
                $this->addFlash('success','Category created successfully.');

                return $this->redirectToRoute('blog.category.index');
            } catch (\DomainException $e){
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error',$e->getMessage());
            }
        }
        return $this->render('app/blog/category/create.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/blog/category/edit/{slug}", name="blog.category.edit")
     * @param Request $request
     * @param Category\Create\Handler $handler
     * @return Response
     * @throws \Exception
     */
    public function edit(\App\Model\Blog\Entity\Category\Category $category,
                         Request $request, Category\Edit\Handler $handler): Response
    {
        $command = Category\Edit\Command::fromCategory($category);
        $form = $this->createForm(Category\Edit\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                return $this->redirectToRoute('blog.category.index');
            } catch (\DomainException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $e->getMessage());
            }
        }
        return $this->render('app/blog/category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/blog/category/delete/{id}", name="blog.category.delete")
     * @param Request $request
     * @param Category\Remove\Handler $handler
     * @return Response
     * @throws \Exception
     */
    public function delete(Request $request,
                           \App\Model\Blog\Entity\Category\Category $category,
                           Category\Remove\Handler $handler): Response
    {
        $command = new Category\Remove\Command($category->getId());

        try {
            $handler->handle($command);
            $this->addFlash('success','Category is remove.');

            return $this->redirectToRoute('blog.category.index');
        } catch (\DomainException $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);
            $this->addFlash('error', $e->getMessage());
        }
        return $this->redirectToRoute('blog.category.index');
    }

}