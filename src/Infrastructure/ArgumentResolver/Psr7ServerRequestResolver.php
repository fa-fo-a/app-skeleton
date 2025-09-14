<?php

declare(strict_types=1);

namespace Infrastructure\ArgumentResolver;

use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class Psr7ServerRequestResolver implements ValueResolverInterface
{
    public function __construct(
        private PsrHttpFactory $psrHttpFactory
    ) {
    }

    public function resolve(
        Request $request,
        ArgumentMetadata $argument
    ): iterable {
        if (ServerRequestInterface::class !== $argument->getType()) {
            return [];
        }

        return [
            $this->psrHttpFactory->createRequest($request)
        ];
    }
}
