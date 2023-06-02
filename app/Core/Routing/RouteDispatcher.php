<?php

declare(strict_types=1);

namespace GrigoryGerasimov\LaraLikeRouting\Core\Routing;

use GrigoryGerasimov\LaraLikeRouting\Core\Exceptions\RouterConfig\MissingRouterConfigException;
use GrigoryGerasimov\LaraLikeRouting\Core\Exceptions\RouteController\{
    MissingRouteControllerClass,
    MissingRouteControllerAction
};
use GrigoryGerasimov\LaraLikeRouting\Core\Exceptions\Route\MissingConfigRouteException;
use GrigoryGerasimov\LaraLikeRouting\Core\Helpers\{Helper, ClearStringOptions};

class RouteDispatcher
{
    protected string $requestUri = '/';
    protected string $configRoute;
    protected array $splitRequestUri = [];
    protected array $splitConfigRoute = [];
    protected array $configRouteParamsNames = [];
    protected array $paramsCollection = [];

    public function __construct(
        protected RouterConfig $routerConfig
    )
    {
        $this->configRoute = $this->routerConfig->route;
    }

    public function start(): void
    {
        $this
            ->clearRequestUri()
            ->clearConfigRoute()
            ->pluckConfigRouteParamsNames()
            ->transformRequestUriToPattern()
            ->process();
    }

    private function clearRequestUri(): self
    {
        if ($_SERVER['REQUEST_URI'] !== '/') {
            $this->requestUri = Helper::clearString($_SERVER['REQUEST_URI'], ClearStringOptions::SLASH->value);
        }

        return $this;
    }

    private function clearConfigRoute(): self
    {
        try {
            if (!isset($this->routerConfig)) {
                throw new MissingRouterConfigException('No router config provided');
            }
            if (empty($this->configRoute)) {
                throw new MissingConfigRouteException('No config route');
            }

            $this->configRoute = Helper::clearString($this->configRoute, ClearStringOptions::SLASH->value);
        } catch (MissingRouterConfigException | MissingConfigRouteException $e) {
            die($e->getMessage());
        } catch (\Throwable $e) {
            error_log($e->getTraceAsString());
        }

        return $this;
    }

    private function pluckConfigRouteParamsNames(): self
    {
        $this->splitConfigRoute = explode('/', $this->configRoute);

        foreach ($this->splitConfigRoute as $key => $value) {
            if (preg_match('/{.*}/', $value)) {
                $this->configRouteParamsNames[$key] = Helper::clearString($value, ClearStringOptions::CURLY->value);
            }
        }

        return $this;
    }

    private function transformRequestUriToPattern(): self
    {
        $this->splitRequestUri = explode('/', $this->requestUri);

        foreach ($this->configRouteParamsNames as $key => $configRouteParamName) {
            if (array_key_exists($key, $this->splitRequestUri)) {
                $this->paramsCollection[$configRouteParamName] = $this->splitRequestUri[$key];
                $this->splitRequestUri[$key] = '{.*}';
            }
        }

        $this->requestUri = str_replace('/', '\/', implode('/', $this->splitRequestUri));

        return $this;
    }

    private function process(): void
    {
        try {
            if (!isset($this->routerConfig->controllerName)) {
                throw new MissingRouteControllerClass('No route controller class name provided');
            }
            if (!isset($this->routerConfig->controllerAction)) {
                throw new MissingRouteControllerAction('No route controller action provided');
            }

            extract($this->routerConfig->retrievePropsAsArray(), EXTR_SKIP);

            if (preg_match("/^$this->requestUri$/", $this->configRoute)) {
                !empty($this->paramsCollection) ? (new $controllerName)->$controllerAction(...$this->paramsCollection) : (new $controllerName)->$controllerAction();
            }
        } catch (MissingRouteControllerClass|MissingRouteControllerAction $e) {
            die($e->getMessage());
        } catch (\Throwable $e) {
            error_log($e->getTraceAsString());
        }
    }
}