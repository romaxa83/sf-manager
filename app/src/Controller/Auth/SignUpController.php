<?php
declare(strict_types=1);

namespace App\Controller\Auth;

use App\Controller\ErrorHandler;
use App\Model\User\UseCase\SignUp;
use App\ReadModel\User\UserFetcher;
use App\Security\LoginFormAuthenticator;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class SignUpController extends AbstractController
{
    private $errors;
    /**
     * @var UserFetcher
     */
    private $userFetcher;

    public function __construct(UserFetcher $userFetcher, ErrorHandler $errors)
    {
        $this->errors = $errors;
        $this->userFetcher = $userFetcher;
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
                $this->errors->handle($e);
                $this->addFlash('error',$e->getMessage());
            }
        }
        return $this->render('app/auth/signup.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/signup/{token}", name="auth.signup.confirm")
     *  @param Request $request
     * @param string $token
     * @param SignUp\Confirm\Handler $handler
     * @param UserProviderInterface $userProvider
     * @param GuardAuthenticatorHandler $guardHandler
     * @param LoginFormAuthenticator $authenticator
     * @return Response
     */
    public function confirm(
        Request $request,
        string $token,
        SignUp\Confirm\Handler $handler,
        UserProviderInterface $userProvider,
        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthenticator $authenticator
    ): Response
    {
        if(!$user = $this->userFetcher->findBySignUpConfirmToken($token)) {
            $this->addFlash('error', 'Incorrect or already confirmed token.');

            return $this->redirectToRoute('auth.signup');
        }

        $command = new SignUp\Confirm\Command($token);

        try{
            $handler->handle($command);
            $this->addFlash('success','Почта потвержденна');

            //сразу логиним пользователя
            return $guardHandler->authenticateUserAndHandleSuccess(
                $userProvider->loadUserByUsername($user->email),
                $request,
                $authenticator,
                'main'//указываем firewall из security.yml
            );
        } catch (\DomainException $e){
            $this->errors->handle($e);
            $this->addFlash('error',$e->getMessage());

            return $this->redirectToRoute('home');
        }
    }
}