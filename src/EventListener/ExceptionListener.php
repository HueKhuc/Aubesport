<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Exception\Conflict;
use App\Exception\NotFound;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Validator\Exception\ValidationFailedException;

#[AsEventListener(event: KernelEvents::EXCEPTION)]
class ExceptionListener
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly LoggerInterface $logger
    ) {
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
        } elseif ($exception instanceof Conflict) {
            $response = new JsonResponse(
                ['message' => $exception->getMessage()],
                409
            );
            $this->logger->error($exception->getMessage(), ['trace' => $exception->getTrace()]);
        } else {
            $code = ($exception instanceof NotFound) ? 404 : 500;
            $response = new JsonResponse(
                ['message' => $exception->getMessage()],
                $code
            );
            $this->logger->error($exception->getMessage(), ['trace' => $exception->getTrace()]);
        }

        $exceptionEvent->setResponse($response);
    }
}
