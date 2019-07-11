<?php
declare(strict_types=1);

namespace App\Controller\Blog;

use App\Controller\ErrorHandler;

use App\Model\Blog\Entity\Category\Category as BlogCategory;
use App\Model\Blog\UseCase\Category;
use App\ReadModel\Blog\Category\CategoryFetcher;
use App\ReadModel\Blog\Category\Filter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/blog/category", name="blog.category")
 */
class CategoryController extends AbstractController
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
    public function index(Request $request, CategoryFetcher $categoryFetcher): Response
    {
        $filter = new Filter\Filter();

        $form = $this->createForm(Filter\Form::class, $filter);
        $form->handleRequest($request);

        $pagination = $categoryFetcher->all(
            $filter,
            $request->query->getInt('page', 1),
            self::PER_PAGE,
            $request->query->get('sort', 'title'),
            $request->query->get('direction', 'asc')
        );

        return $this->render('app/blog/category/index.html.twig',[
            'pagination' => $pagination,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/create", name=".create")
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

                return $this->redirectToRoute('blog.category');
            } catch (\DomainException $e){
                $this->errors->handle($e);
                $this->addFlash('error',$e->getMessage());
            }
        }
        return $this->render('app/blog/category/create.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{slug}", name=".edit")
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
                return $this->redirectToRoute('blog.category');
            } catch (\DomainException $e) {
                $this->errors->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }
        return $this->render('app/blog/category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/delete/{id}", name=".delete")
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
        } catch (\DomainException $e) {
            $this->errors->handle($e);
            $this->addFlash('error', $e->getMessage());
        }
        return $this->redirectToRoute('blog.category');
    }

    /**
     * @Route("/{id}/activate", name=".activate", methods={"POST"})
     * @param BlogCategory $category
     * @param Request $request
     * @param Category\Status\Handler $handler
     * @return Response
     */
    public function activate(BlogCategory $category, Request $request, Category\Status\Handler $handler): Response
    {
        if (!$this->isCsrfTokenValid('active', $request->request->get('token'))) {

            return $this->redirectToRoute('blog.category.show', ['slug' => $category->getSlug()]);
        }

        $command = new Category\Status\Command($category->getId(),BlogCategory::STATUS_ACTIVE);

        try {
            $handler->handle($command);
        } catch (\DomainException $e) {
            $this->errors->handle($e);
            $this->addFlash('error', $e->getMessage());
        }
        return $this->redirectToRoute('blog.category.show', ['slug' => $category->getSlug()]);
    }

    /**
     * @Route("/{id}/inactivate", name=".inactivate", methods={"POST"})
     * @param BlogCategory $category
     * @param Request $request
     * @param Category\Status\Handler $handler
     * @return Response
     */
    public function inactivate(BlogCategory $category, Request $request, Category\Status\Handler $handler): Response
    {
        if (!$this->isCsrfTokenValid('block', $request->request->get('token'))) {

            return $this->redirectToRoute('blog.category.show', ['slug' => $category->getSlug()]);
        }

        $command = new Category\Status\Command($category->getId(),BlogCategory::STATUS_INACTIVE);

        try {
            $handler->handle($command);
        } catch (\DomainException $e) {
            $this->errors->handle($e);
            $this->addFlash('error', $e->getMessage());
        }
        return $this->redirectToRoute('blog.category.show', ['slug' => $category->getSlug()]);
    }

    /**
     * @Route("/{slug}", name=".show")
     * @param BlogCategory $category
     * @return Response
     */
    public function show(BlogCategory $category): Response
    {
        return $this->render('app/blog/category/show.html.twig', compact('category'));
    }
}