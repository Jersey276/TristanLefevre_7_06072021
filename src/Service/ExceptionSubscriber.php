<?php
namespace App\Service;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Event subscriber manage exception in this api
 * @author Tristan
 * @version 1
 */
class ExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * Get subscribed Events with respectedpriority
     */
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
    
    /**
     * Send Error like Response
     * @param ExceptionEvent $event
     */
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
    // Code use for developpment only
    //    if ($result['code'] == Response::HTTP_INTERNAL_SERVER_ERROR) {
    //        $result['body']['stacktrace'] = $exception->getTrace();
    //    }
        $response = new JsonResponse($result['body'], $result['code']);
        $event->setResponse($response);
    }

    /**
     * Log exception when thrown
     * @param ExceptionEvent $evene
     */
    public function logException(ExceptionEvent $event) : void
    {
        $exception = $event->getThrowable();
        if (! $exception instanceof HttpExceptionInterface) {
            error_log($exception->getMessage());
        }
    }
}
