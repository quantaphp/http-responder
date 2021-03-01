<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface;

use Laminas\Diactoros\ResponseFactory;

use Quanta\Http\Responder;

describe('Responder', function () {

    beforeEach(function () {
        $this->responder = new Responder(new ResponseFactory);
    });

    describe('->__invoke()', function () {

        context('when the given response code is 301', function () {

            context('when no body is given', function () {

                it('should throw an InvalidArgumentException', function () {
                    $test = fn () => ($this->responder)(301);

                    expect($test)->toThrow(new InvalidArgumentException);
                });

            });

            context('when the given body is null', function () {

                it('should throw an InvalidArgumentException', function () {
                    $test = fn () => ($this->responder)(301, null);

                    expect($test)->toThrow(new InvalidArgumentException);
                });

            });

            context('when the given body is a string', function () {

                it('should return a permament redirect with the given body as url', function () {
                    $test = ($this->responder)(301, '/test');

                    expect($test)->toBeAnInstanceOf(ResponseInterface::class);
                    expect($test->getStatusCode())->toEqual(301);
                    expect($test->getHeaderLine('location'))->toEqual('/test');
                });

            });

            context('when the given body is a Traversable', function () {

                it('should throw an InvalidArgumentException', function () {
                    $test = fn () => ($this->responder)(301, new ArrayIterator([]));

                    expect($test)->toThrow(new InvalidArgumentException);
                });

            });

            context('when the given body is an array', function () {

                it('should throw an InvalidArgumentException', function () {
                    $test = fn () => ($this->responder)(301, []);

                    expect($test)->toThrow(new InvalidArgumentException);
                });

            });

        });

        context('when the given response code is 302', function () {

            context('when no body is given', function () {

                it('should throw an InvalidArgumentException', function () {
                    $test = fn () => ($this->responder)(302);

                    expect($test)->toThrow(new InvalidArgumentException);
                });

            });

            context('when the given body is null', function () {

                it('should throw an InvalidArgumentException', function () {
                    $test = fn () => ($this->responder)(302, null);

                    expect($test)->toThrow(new InvalidArgumentException);
                });

            });

            context('when the given body is a string', function () {

                it('should return a permament redirect with the given body as url', function () {
                    $test = ($this->responder)(302, '/test');

                    expect($test)->toBeAnInstanceOf(ResponseInterface::class);
                    expect($test->getStatusCode())->toEqual(302);
                    expect($test->getHeaderLine('location'))->toEqual('/test');
                });

            });

            context('when the given body is a Traversable', function () {

                it('should throw an InvalidArgumentException', function () {
                    $test = fn () => ($this->responder)(302, new ArrayIterator([]));

                    expect($test)->toThrow(new InvalidArgumentException);
                });

            });

            context('when the given body is an array', function () {

                it('should throw an InvalidArgumentException', function () {
                    $test = fn () => ($this->responder)(302, []);

                    expect($test)->toThrow(new InvalidArgumentException);
                });

            });

        });

        context('when the given code is neither 301 or 302', function () {

            context('when no body is given', function () {

                it('should return an empty response with the given code', function () {
                    $test = ($this->responder)(200);

                    expect($test)->toBeAnInstanceOf(ResponseInterface::class);
                    expect($test->getStatusCode())->toEqual(200);
                    expect($test->getHeaders())->toEqual([]);
                    expect((string) $test->getBody())->toEqual('');
                });

            });

            context('when the given body is null', function () {

                it('should return an empty response with the given code', function () {
                    $test = ($this->responder)(200, null);

                    expect($test)->toBeAnInstanceOf(ResponseInterface::class);
                    expect($test->getStatusCode())->toEqual(200);
                    expect($test->getHeaders())->toEqual([]);
                    expect((string) $test->getBody())->toEqual('');
                });

            });

            context('when the given body is a string', function () {

                it('should return a response with the given code, the given string as body and text/html as content type', function () {
                    $test = ($this->responder)(200, 'test');

                    expect($test)->toBeAnInstanceOf(ResponseInterface::class);
                    expect($test->getStatusCode())->toEqual(200);
                    expect($test->getHeaderLine('Content-type'))->toEqual('text/html');
                    expect((string) $test->getBody())->toEqual('test');
                });

            });

            context('when the given body is a Traversable', function () {

                it('should return a response with the given code, the given Traversable as body and application/json as content type', function () {
                    $data = ['k1' => 'v1', 'k2' => 'v2'];

                    $test = ($this->responder)(200, new ArrayIterator($data));

                    expect($test)->toBeAnInstanceOf(ResponseInterface::class);
                    expect($test->getStatusCode())->toEqual(200);
                    expect($test->getHeaderLine('Content-type'))->toEqual('application/json');
                    expect((string) $test->getBody())->toEqual(json_encode($data));
                });

            });

            context('when the given body is an array', function () {

                it('should return a response with the given code, the given array as body and application/json as content type', function () {
                    $data = ['k1' => 'v1', 'k2' => 'v2'];

                    $test = ($this->responder)(200, $data);

                    expect($test)->toBeAnInstanceOf(ResponseInterface::class);
                    expect($test->getStatusCode())->toEqual(200);
                    expect($test->getHeaderLine('Content-type'))->toEqual('application/json');
                    expect((string) $test->getBody())->toEqual(json_encode($data));
                });

            });

        });

    });

});
