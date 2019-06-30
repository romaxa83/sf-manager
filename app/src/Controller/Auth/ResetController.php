<?php
declare(strict_types=1);

namespace App\Controller\Auth;

use App\Controller\ErrorHandler;
use App\Model\User\UseCase\Reset;
use App\ReadModel\User\UserFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResetController extends AbstractController
{
    private $errors;

    public function __construct(ErrorHandler $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @Route("/reset", name="auth.reset")
     * @param Request $request
     * @param reset\Request\Handler $handler
     * @return Response
     * @throws \Exception
     */
    public function request(Request $request, Reset\Request\Handler $handler): Response
    {
        $command = new Reset\Request\Command();
        //создаем форму и оборачиваем в класс command
        $form = $this->createForm(Reset\Request\Form::class,$command);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            try{
                $handler->handler($command);
                $this->addFlash('success','Check your email.');

                return $this->redirectToRoute('home');
            } catch (\DomainException $e){
                $this->errors->handle($e);
                $this->addFlash('error',$e->getMessage());
            }
        }
        return $this->render('app/auth/reset/request.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/reset/{token}", name="auth.reset.reset")
     * @param string $token
     * @param Reset\Reset\Handler $handler
     * @return Response
     * @throws \Exception
     */
    public function reset(
        string $token,
        Request $request,
        Reset\Reset\Handler $handler,
        UserFetcher $users
    ): Response
    {
        //проверяем у пользователя валидный ли его токен
        if(!$users->existsByResetToken($token)){
            $this->addFlash('error','Incorrect or already token.');
            return $this->redirectToRoute('home');
        }

        $command = new Reset\Reset\Command($token);

        $form = $this->createForm(Reset\Reset\Form::class,$command);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Password is successfully changed.');

                return $this->redirectToRoute('home');
            } catch (\DomainException $e) {
                $this->errors->handle($e);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/auth/reset/reset.html.twig',[
            'form' => $form->createView()
        ]);
    }
}