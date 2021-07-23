<?php
namespace App\Service;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents() : array
    {
        // return the subscribed events, their methods and priorities
        return [
            KernelEvents::EXCEPTION => [
                ['processException', 10],
                ['logException', 0],
            ],
        ];
    }
    
    public function processException(ExceptionEvent $event) : void
    {
        $exception = $event->getThrowable();
        if ($exception instanceof HttpExceptionInterface) {
            $result['code'] = $exception->getStatusCode();
        } elseif ($exception instanceof AccessDeniedException) {
            $result['code'] = Response::HTTP_UNAUTHORIZED;
        } else {
            $result['code'] = Response::HTTP_INTERNAL_SERVER_ERROR;
        }
        $result['body'] = [
            'code' => $result['code'],
            'message' => $exception->getMessage()
        ];
        if ($result['code'] == Response::HTTP_INTERNAL_SERVER_ERROR) {
            $result['body']['stacktrace'] = $exception->getTrace();
        }
        $response = new JsonResponse($result['body'], $result['code']);
        $event->setResponse($response);
    }

    public function logException(ExceptionEvent $event) : void
    {
        $exception = $event->getThrowable();
        if (! $exception instanceof HttpExceptionInterface) {
            error_log($exception->getMessage());
        }
    }
}
