<?php

declare(strict_types=1);

namespace App\EventListener;

use Throwable;
use App\Exception\Conflict;
use App\Exception\NotFound;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
        $statusCode = $this->getStatusCodeForException($exception);
        $message = $this->getMessageForException($exception);


        if ($exception->getPrevious() instanceof ValidationFailedException) {
            $response = new JsonResponse($message, $statusCode, json: true);
        } else {
            $response = new JsonResponse(['message' => $message], $statusCode);
        }

        if ($statusCode === 500) {
            $this->logger->error($message, ['trace' => $exception->getTrace()]);
        }

        $exceptionEvent->setResponse($response);
    }

    private function getStatusCodeForException(Throwable $exception): int
    {
        switch (true) {
            case $exception->getPrevious() instanceof ValidationFailedException:
                return 422;
            case $exception instanceof Conflict:
                return 409;
            case $exception instanceof AccessDeniedHttpException:
                return 403;
            case $exception instanceof NotFound:
            case $exception instanceof NotFoundHttpException:
                return 404;
            default:
                return 500;
        }
    }

    private function getMessageForException(Throwable $exception): string
    {
        $previous = $exception->getPrevious();

        if ($previous instanceof ValidationFailedException) {
            return $this->serializer->serialize($previous->getViolations(), 'json');
        } else {
            return $exception->getMessage();
        }
    }
}
