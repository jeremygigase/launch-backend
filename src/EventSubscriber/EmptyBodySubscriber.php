<?php
namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class EmptyBodySubscriber implements EventSubscriberInterface
{
    const ERROR_EMPTY_BODY = "The body of the POST/PUT method cannot be empty";
    const ERROR_EMPTY_BODY_CODE = 400;
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => ['handleEmptyBody',EventPriorities::POST_DESERIALIZE]
        ];
    }


    public function handleEmptyBody(ExceptionEvent $event)
    {
        $request = $event->getRequest();
        $method = $request->getMethod();
        $data = $event->getRequest()->get('data');
        $route = $request->get('_route');

        if (!in_array($method, [Request::METHOD_POST, Request::METHOD_PUT]) ||
            in_array($request->getContentType(), ['html', 'form']) ||
            substr($route, 0, 3) !== 'api') {
            return;
        }

        if(null ===$data)
        {
            $event->setResponse(new JsonResponse(["Error"=>self::ERROR_EMPTY_BODY,"Error Code"=>self::ERROR_EMPTY_BODY_CODE],Response::HTTP_BAD_REQUEST));
        }

    }
}