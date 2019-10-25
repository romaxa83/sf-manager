<?php

declare(strict_types=1);

namespace App\Controller;

use App\Annotation\RequiresUserCredits;
use phpcent\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RequiresUserCredits()
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/",name="home")
     */
    public function index(Client $centrifugo): Response
    {
        $centrifugo->publish('alerts', [
            'message' => 'Halo!'
        ]);

        return $this->render('app/home.html.twig');
    }
}