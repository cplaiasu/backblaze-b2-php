<?php

namespace Zaxbux\BackblazeB2\Http\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zaxbux\BackblazeB2\Client\Exception\B2APIException;
use Zaxbux\BackblazeB2\Client\Exception\NotFoundException;
use Zaxbux\BackblazeB2\Client\Exception\UnauthorizedException;
use Zaxbux\BackblazeB2\Client\Exception\ValidationException;
use Zaxbux\BackblazeB2\Http\Response;

class ExceptionMiddleware
{
	public function __invoke(callable $handler)
	{
		return function (RequestInterface $request, array $options = []) use ($handler) {
			$response = $handler($request, $options);
			if ($this->isSuccessful($response)) {
				return $response;
			}
			$this->handleErrorResponse($response);
		};
	}

	public function isSuccessful(ResponseInterface $response)
	{
		return $response->getStatusCode() <= 299;
		//return $response->getStatusCode() == Response::HTTP_OK || Response::HTTP_NO_CONTENT || Response::HTTP_PARTIAL_CONTENT;
		//return $response->getStatusCode() < Response::HTTP_BAD_REQUEST;
	}

	public function handleErrorResponse(ResponseInterface $response)
	{
		switch ($response->getStatusCode()) {
			case Response::HTTP_UNPROCESSABLE_ENTITY:
				throw new ValidationException(json_decode($response->getBody(), true));
			case Response::HTTP_NOT_FOUND:
				throw new NotFoundException;
			case Response::HTTP_UNAUTHORIZED:
				throw new UnauthorizedException;
			default:
				throw new B2APIException((string) $response->getBody());
		}
	}
}