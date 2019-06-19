<?php
namespace App\Controller\Auth\OAuth;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Контролер для авторизация через facebook
 */
class FacebookController extends Controller
{
    /**
     * @Route("/oauth/facebook", name="oauth.facebook")
     * @param ClientRegistry $clientRegistry
     * @return Response
     */
    public function connectAction(ClientRegistry $clientRegistry): Response
    {
        return $clientRegistry
            ->getClient('facebook_main')
            ->redirect(['public_profile']);
    }

    /**
     * @Route("/oauth/facebook/check", name="oauth.facebook_check")
     * @return Response
     */
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry): Response
    {
        return $this->redirectToRoute('home');
    }
}