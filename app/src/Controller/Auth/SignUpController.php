<?php
declare(strict_types=1);

namespace App\Controller\Auth;

use App\Model\User\UseCase\SignUp;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignUpController extends AbstractController
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
     * @Route("/signup", name="auth.signup")
     * @param Request $request
     * @param SignUp\Request\Handler $handler
     * @return Response
     * @throws \Exception
     */
    public function request(Request $request, SignUp\Request\Handler $handler): Response
    {
        $command = new SignUp\Request\Command();
        //создаем форму и оборачиваем в класс command
        $form = $this->createForm(SignUp\Request\Form::class,$command);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            try{
                $handler->handle($command);
                $this->addFlash('success','Check your email.');

                return $this->redirectToRoute('home');
            } catch (\DomainException $e){
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error',$e->getMessage());
            }
        }
        return $this->render('app/auth/signup.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/signup/{token}", name="auth.signup.confirm")
     * @param string $token
     * @param SignUp\Confirm\Handler $handler
     * @return Response
     * @throws \Exception
     */
    public function confirm(string $token, SignUp\Confirm\Handler $handler): Response
    {
        $command = new SignUp\Confirm\Command($token);

        try{
            $handler->handle($command);
            $this->addFlash('success','Почта потвержденна');

            return $this->redirectToRoute('home');
        } catch (\DomainException $e){
            $this->logger->error($e->getMessage(), ['exception' => $e]);
            $this->addFlash('error',$e->getMessage());

            return $this->redirectToRoute('home');
        }
    }
}