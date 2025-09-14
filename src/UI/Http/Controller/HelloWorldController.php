<?php

declare(strict_types=1);

namespace UI\Http\Controller;

use UI\Http\Factory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HelloWorldController
{
    public const string ROUTE = '/';

    public function __construct(
        private ResponseFactory $responseFactory
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return $this->responseFactory->build(
            200,
            json_encode(
                [
                    'hello' => 'world',
                ],
                JSON_PRETTY_PRINT
            )
        );
    }
}
