<?php

namespace App\Security\OAuth;


use App\Model\User\UseCase\Network\Auth\Command;
use App\Model\User\UseCase\Network\Auth\Handler;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class FacebookAuthenticator extends SocialAuthenticator
{
    private $urlGenerator;
    private $clientRegistry;
    private $handler;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        ClientRegistry $clientRegistry,
        Handler $handler
    )
    {
        $this->clientRegistry = $clientRegistry;
        $this->urlGenerator = $urlGenerator;
        $this->handler = $handler;
    }

    public function supports(Request $request)
    {
        return $request->attributes->get('_route') === 'oauth.facebook_check';
    }

    /**
     * @param Request $request
     * @return \League\OAuth2\Client\Token\AccessToken|mixed
     */
    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getFacebookClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider): UserInterface
    {
        $facebookUser = $this->getFacebookClient()->fetchUserFromToken($credentials);
        //при авторизации сохраняем username пользователя как "название_сети:id_сети"
        $network = 'facebook';
        $id = $facebookUser->getId();
        $username = $network . ':' . $id;
        $command = new Command($network, $id);
        $command->firstName = $facebookUser->getFirstName();
        $command->lastName = $facebookUser->getLastName();

        try {
            return $userProvider->loadUserByUsername($username);
        } catch (UsernameNotFoundException $e) {

            $this->handler->handle($command);

            return $userProvider->loadUserByUsername($username);
        }
    }

    /**
     * @return FacebookClient|OAuth2Client
     */
    private function getFacebookClient()
    {
        return $this->clientRegistry->getClient('facebook_main');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse($this->urlGenerator->generate('home'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }

    // ...
}