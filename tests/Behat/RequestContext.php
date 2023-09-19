<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

class RequestContext implements Context
{
    public function __construct(
        protected KernelInterface $kernel,
        protected ?Response $response = null,
        protected EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @When I send a post request to :uri with
     */
    public function iSendAPostRequest(string $uri, PyStringNode $string): void
    {
        $this->response = $this->kernel->handle(Request::create(
            $uri,
            Request::METHOD_POST,
            [],
            [],
            [],
            [],
            $string->getRaw()
        ));
    }

    /**
     * @Then I should receive a status code :statusCode
     */

    public function iShouldReceiveStatusCode(int $statusCode)
    {
        if ($this->response === null) {
            throw new \RuntimeException('No response received');
        }

        TestCase::assertSame($statusCode, $this->response->getStatusCode());
    }

    /**
     * @Given a user with email :email
     */

     public function aUserWithEmail(string $email)
     {
        $user = new User();
        $user->setEmail($email);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
     }
}
