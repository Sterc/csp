<?php

namespace Sterc\CSP\Middlewares;

use MODX\Revolution\modX;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Slim\Psr7\Stream;

class Admin
{
    protected modX $modx;

    public function __construct(modX $modx)
    {
        $this->modx = $modx;
    }

    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        if (!$this->modx->user || !$this->modx->user->hasSessionContext('mgr')) {
            $response = new Response();
            $body = new Stream(fopen('php://temp', 'wb'));
            $body->write(json_encode('Access Denied', JSON_THROW_ON_ERROR));
            $response = $response->withBody($body);

            return $response
                ->withStatus(401)
                ->withHeader('Content-Type', 'application/json; charset=utf-8');
        }

        return $handler->handle($request);
    }
}