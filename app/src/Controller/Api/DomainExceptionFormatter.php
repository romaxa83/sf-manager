<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\ErrorHandler;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

//создаем слушателя на exception'ы
class DomainExceptionFormatter implements EventSubscriberInterface
{
    private $errors;

    public function __construct(ErrorHandler $errors)
    {
        $this->errors = $errors;
    }

    // подписываемся на событие 'onKernelException' которое сработает
    // когда в системе будет выкинуто любое исключение
    public static function getSubscribedEvents(): array
    {
        return array(
            KernelEvents::EXCEPTION => 'onKernelException'
        );
    }

    // здесь получаем обьект событие(в нашем случае это исключение)
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getException();
        $request = $event->getRequest();

        // проверяем на DomainException
        if (!$exception instanceof \DomainException) {
            return;
        }

        // проверяем что исключение кинуто в api
        if (strpos($request->attributes->get('_route'), 'api.') !== 0) {
            return;
        }

        $this->errors->handle($exception);

        $event->setResponse(new JsonResponse([
            'error' => [
                'code' => 400,
                'message' => $exception->getMessage(),
            ]
        ], 400));
    }
}
