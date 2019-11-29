<?php declare(strict_types=1);

namespace Sensors\Application\DataObject;

final class Route
{
    /** @var string */
    private $route;

    /** @var string */
    private $handlerClass;

    /** @var string */
    private $method;

    private function __construct(string $route, string $handlerClass, string $method)
    {
        $this->route = $route;
        $this->handlerClass = $handlerClass;
        $this->method = $method;
    }

    public static function create(string $route, string $handlerClass, string $method = 'GET'): self
    {
        return new self($route, $handlerClass, $method);
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getHandlerClass(): string
    {
        return $this->handlerClass;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}
