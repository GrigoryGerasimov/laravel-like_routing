<?php

declare(strict_types=1);

namespace GrigoryGerasimov\LaraLikeRouting\Core\Routing;

use GrigoryGerasimov\LaraLikeRouting\Core\Exceptions\RouterConfig\MissingRouterConfigException;
use GrigoryGerasimov\LaraLikeRouting\Core\Helpers\{Helper, ClearStringOptions};

class RouteDispatcher
{
    protected string $requestUri = '/';
    protected string $configRoute;
    protected array $splitRequestUri;
    protected array $splitConfigRoute;
    protected array $paramMap;

    public function __construct(
        protected RouterConfig $routerConfig
    )
    {
        $this->configRoute = $this->routerConfig->route;
    }

    public function process(): void
    {
        $this->saveRequestUri();
        $this->getParamMap();
        $this->makeRegexRequest();
    }

    public function saveRequestUri(): void
    {
        try {
            if (!isset($this->routerConfig)) {
                throw new MissingRouterConfigException('No router config provided');
            }

            if ($_SERVER['REQUEST_URI'] !== '/') {
                $this->requestUri = $_SERVER['REQUEST_URI'];
                $this->requestUri = Helper::clearString($this->requestUri, ClearStringOptions::SLASH->value);
            }

            $this->configRoute = Helper::clearString($this->configRoute, ClearStringOptions::SLASH->value);
        } catch (MissingRouterConfigException $e) {
            die($e->getMessage());
        } catch (\Throwable $e) {
            error_log($e->getTraceAsString());
        }
    }

    public function getParamMap(): void
    {
        $this->splitConfigRoute = explode('/', $this->configRoute);

        foreach ($this->splitConfigRoute as $key => $value) {
            if (preg_match('/{.*}/', $value)) {
                $this->paramMap[$key] = $value;
            }
        }
    }

    public function makeRegexRequest(): void
    {
        $this->splitRequestUri = explode('/', $this->requestUri);

        foreach($this->splitRequestUri as $key => $value) {
            if (array_key_exists($key, $this->paramMap)) {
                print_r($this->paramMap[$key]);
            }
        }
    }
}