<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

class RequestContext implements Context
{
    public function __construct(
        protected KernelInterface $kernel,
        protected ?Response $response = null
    ) {
    }

    /**
     * @When I send a post request to :uri with
     */
    public function iSendAPostRequest(string $uri, PyStringNode $string): void
    {
        $this->response = $this->kernel->handle(Request::create(
            sprintf('/api/%s', $uri),
            Request::METHOD_POST,
            [],
            [],
            [],
            [],
            $string->getRaw()
        ));
    }

    /**
     * @Then I should receive a success response
     */
    public function iReceiveSuccessReponse(): void
    {
        if ($this->response === null) {
            throw new \RuntimeException('No response received');
        }

        TestCase::assertSame(200, $this->response->getStatusCode());
    }
}
