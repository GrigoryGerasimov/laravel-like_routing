<?php

declare(strict_types=1);

namespace GrigoryGerasimov\LaraLikeRouting\Core\Routing;

class RouterConfig
{
    protected string $name;
    protected string $middleware;

    public function __construct(
        protected readonly string $route,
        protected readonly string $controllerName,
        protected readonly string $controllerAction
    ) {}

    public function __get($prop): mixed
    {
        return $this->$prop;
    }

    public function __isset($prop): bool
    {
        return property_exists($this, $prop);
    }

    public function retrievePropsAsArray(): array
    {
        return get_object_vars($this);
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function middleware(string $middleware): self
    {
        $this->middleware = $middleware;

        return $this;
    }
}