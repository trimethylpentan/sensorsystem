<?php declare(strict_types=1);

namespace Sensors\Application\Controller;

use Http\HttpRequest;
use Http\HttpResponse;
use JsonSerializable;
use Sensors\Application\DataObject\Route;

abstract class AbstractController
{
    /** @var HttpRequest */
    private $request;

    /** @var HttpResponse */
    private $response;

    /** @var Route */
    private $matchedRoute;

    public function prepare(HttpRequest $request, HttpResponse $response, Route $matchedRoute): void
    {
        $this->request      = $request;
        $this->response     = $response;
        $this->matchedRoute = $matchedRoute;
    }

    abstract public function handle(array $vars);

    protected function getRequest(): HttpRequest
    {
        return $this->request;
    }

    protected function getResponse(): HttpResponse
    {
        return $this->response;
    }

    protected function getMatchedRoute(): Route
    {
        return $this->matchedRoute;
    }

    /**
     * @param array | JsonSerializable $data
     * @param int $statusCode
     */
    protected function jsonResponse($data, int $statusCode = 200): void
    {
        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setStatusCode($statusCode);
        $this->response->setContent(json_encode($data, JSON_THROW_ON_ERROR));
    }
}
