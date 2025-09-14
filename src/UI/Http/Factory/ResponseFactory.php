<?php

declare(strict_types=1);

namespace UI\Http\Factory;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class ResponseFactory
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private StreamFactoryInterface $streamFactory
    ) {
    }

    public function build(
        int $responseCode = 200,
        string $body = ''
    ): ResponseInterface {
        $response = $this
            ->responseFactory
            ->createResponse($responseCode)
        ;

        return $response->withBody(
            $this->streamFactory->createStream($body)
        );
    }
}
