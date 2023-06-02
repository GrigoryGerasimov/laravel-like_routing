<?php

declare(strict_types=1);

namespace GrigoryGerasimov\LaraLikeRouting\Core\Routing;

use GrigoryGerasimov\LaraLikeRouting\Core\Exceptions\RouterConfig\MissingRouterConfigException;
use GrigoryGerasimov\LaraLikeRouting\Core\Exceptions\RouteController\{MissingRouteControllerClass, MissingRouteControllerAction};
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
        $this->run();
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
                $this->paramMap[$key] = Helper::clearString($value, ClearStringOptions::CURLY->value);
            }
        }
    }

    public function makeRegexRequest(): void
    {
        $this->splitRequestUri = explode('/', $this->requestUri);

        foreach($this->paramMap as $key => $value) {
            if (array_key_exists($key, $this->splitRequestUri)) {
                $this->splitRequestUri[$key] = '{.*}';
            }
        }

        $this->requestUri = str_replace('/', '\/', implode('/', $this->splitRequestUri));
    }

    public function run(): void
    {
        try {
            if (!isset($this->routerConfig->controllerName)) {
                throw new MissingRouteControllerClass('No route controller class name provided');
            }
            if (!isset($this->routerConfig->controllerAction)) {
                throw new MissingRouteControllerAction('No route controller action provided');
            }

            extract($this->routerConfig->retrievePropsAsArray(), EXTR_SKIP);

            if (preg_match("/$this->requestUri/", $this->configRoute)) {
                (new $controllerName)->$controllerAction();
            }
        } catch (MissingRouteControllerClass | MissingRouteControllerAction $e) {
            die($e->getMessage());
        } catch (\Throwable $e) {
            error_log($e->getTraceAsString());
        }
    }
}