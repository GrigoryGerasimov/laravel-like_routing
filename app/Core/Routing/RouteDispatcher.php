<?php

declare(strict_types=1);

namespace GrigoryGerasimov\LaraLikeRouting\Core\Routing;

use GrigoryGerasimov\LaraLikeRouting\Core\Exceptions\RouterConfig\MissingRouterConfigException;
use GrigoryGerasimov\LaraLikeRouting\Core\Helpers\{Helper, ClearStringOptions};

class RouteDispatcher
{
    private string $requestUri = '/';
    private string $configRoute;

    public function __construct(
        protected RouterConfig $routerConfig
    ) {
        $this->configRoute = $this->routerConfig->route;
    }

    public function process(): void
    {
        $this->saveRequestUri();
        $this->getParamMap();
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
        $splitRequestUri = explode('/', $this->requestUri);
        var_dump($this->configRoute);
        $splitConfigRoute = explode('/', $this->configRoute);


        echo '<pre>';
        print_r($splitRequestUri);
        echo '</pre>';
        echo '<pre>';
        print_r($splitConfigRoute);
        echo '</pre>';
    }

    public function makeRegexRequest()
    {

    }
}