<?php

namespace Sterc\CSP;

use DI\Bridge\Slim\Bridge;
use DI\Container;
use MODX\Revolution\modSystemEvent;
use MODX\Revolution\modX;
use Slim\Routing\RouteCollectorProxy;
use Sterc\CSP\Controllers\Admin\Directives;
use Sterc\CSP\Controllers\Admin\Groups;
use Sterc\CSP\Middlewares\Admin;
use Sterc\CSP\Models\Directive;
use Vesp\Services\Eloquent;

class App
{
    protected modX $modx;

    protected const BASE_PATH = '/sterc-csp';

    public function __construct(modX $modx)
    {
        $this->modx = $modx;
    }

    public function run(): void
    {
        $container = new Container();
        $container->set(modX::class, $this->modx);
        $container->set(Eloquent::class, new Services\Eloquent($this->modx));

        $app = Bridge::create($container);
        $app->addBodyParsingMiddleware();
        $app->addRoutingMiddleware();
        $app->setBasePath($this::BASE_PATH);
        $this::setRoutes($app);

        try {
            $app->run();
        } catch (\Throwable $e) {
            http_response_code($e->getCode());
            echo json_encode($e->getMessage());
        }
    }

    protected static function setRoutes(\Slim\App $app): void
    {
        $app->group(
            '/admin',
            static function (RouteCollectorProxy $group) {
                $group->any('/groups[/{id:\d+}]', Groups::class);
                $group->any('/directives[/{key}]', Directives::class);
            }
        )->add(Admin::class);
    }

    protected function setHeaders(): void
    {
        new Services\Eloquent($this->modx);

        $headers = [];
        /** @var Directive $directive */
        foreach (Directive::query()->where('active', true)->cursor() as $directive) {
            if (empty($directive->value) || !is_array($directive->value)) {
                continue;
            }
            if ($values = array_filter(array_map('trim', $directive->value))) {
                $headers[] = $directive->key . ' ' . implode(' ', $values);
            }
        }

        if (!empty($headers)) {
            header('Content-Security-Policy: ' . implode('; ', $headers));
        }
    }

    public function handleEvent(?modSystemEvent $event): void
    {
        if (!$event) {
            return;
        }

        if ($event->name === 'OnHandleRequest') {
            if (strpos($_SERVER['REQUEST_URI'], $this::BASE_PATH) === 0) {
                $this->run();
                exit();
            }
            if ($this->modx->context !== 'mgr') {
                $this->setHeaders();
            }
        }
    }
}