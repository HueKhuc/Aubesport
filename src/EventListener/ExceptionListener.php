<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Exception\NotFound;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Validator\Exception\ValidationFailedException;

#[AsEventListener(event: KernelEvents::EXCEPTION)]
class ExceptionListener
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    public function __invoke(ExceptionEvent $exceptionEvent): void
    {
        $exception = $exceptionEvent->getThrowable();
        $previous = $exception->getPrevious();

        if ($previous instanceof ValidationFailedException) {
            $response = new JsonResponse(
                $this->serializer->serialize($previous->getViolations(), 'json'),
                422,
                json: true
            );
        } else {
            $code = ($exception instanceof NotFound) ? 404 : 500;
            $response = new JsonResponse(
                ['message' => $exception->getMessage()],
                $code
            );
        }

        $exceptionEvent->setResponse($response);
    }
}
