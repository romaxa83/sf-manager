<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use App\ReadModel\User\UserFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowController extends AbstractController
{
    /**
     * @var UserFetcher
     */
    private $userFetcher;

    public function __construct(UserFetcher $userFetcher)
    {
        $this->userFetcher = $userFetcher;
    }

    /**
     * @Route("/profile",name="profile")
     * @return Response
     */
    public function show(): Response
    {
        $user = $this->userFetcher->get($this->getUser()->getId());

        return $this->render('app/profile/show.html.twig',compact('user'));
    }
}