<?php

declare(strict_types=1);

namespace Quanta\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;

final class Responder
{
    /**
     * @param \Psr\Http\Message\ResponseFactoryInterface    $factory
     * @param int                                           $options
     * @param int                                           $depth
     */
    public function __construct(
        private ResponseFactoryInterface $factory,
        private int $options = 0,
        private int $depth = 512,
    ) {}

    /**
     * Proxy of response method.
     *
     * @param int                       $code
     * @param string|iterable<mixed>    $body
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(int $code, string|iterable $body = null): ResponseInterface
    {
        return $this->response($code, $body);
    }

    /**
     * Return a response according to the given code and body.
     *
     * @param int                       $code
     * @param string|iterable<mixed>    $body
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \InvalidArgumentException
     */
    public function response(int $code, string|iterable $body = null): ResponseInterface
    {
        if ($code == 301 || $code == 302) {
            if (is_string($body)) {
                return $this->factory->createResponse($code)->withHeader('Location', $body);
            }

            throw new \InvalidArgumentException('Redirect responses (301 and 302 codes) must have a string body (the url)');
        }

        if (is_null($body)) {
            return $this->factory->createResponse($code);
        }

        if (is_string($body)) {
            $response = $this->factory->createResponse($code)->withHeader('Content-type', 'text/html');

            $response->getBody()->write($body);

            return $response;
        }

        $data = $body instanceof \Traversable
            ? iterator_to_array($body)
            : $body;

        try {
            /**
             * phpstan does not understand $this->options | JSON_THROW_ON_ERROR
             * @var string
             */
            $body = json_encode($body, $this->options | JSON_THROW_ON_ERROR, $this->depth);
        }

        catch (\JsonException $e) {
            throw new \InvalidArgumentException('Error while encoding the given body as json', 0, $e);
        }

        $response = $this->factory->createResponse($code)->withHeader('Content-type', 'application/json');

        $response->getBody()->write($body);

        return $response;
    }
}
