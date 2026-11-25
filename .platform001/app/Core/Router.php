<?php
declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = ['GET' => [], 'POST' => []];

    public function __construct(private Container $container, array $map)
    {
        foreach ($map as $route) $this->add(...$route);
    }

    public function add(string $method, string $path, array $handler, array $middlewares = []): void
    {
        [$regex, $params] = $this->compile($path);
        $this->routes[$method][] = compact('path', 'regex', 'params', 'handler', 'middlewares');
    }

    private function compile(string $path): array
    {
        $params = [];
        $regex = preg_replace_callback('#\{([a-z_]+)\}#i', function ($m) use (&$params) {
            $params[] = $m[1];
            return '([^/]+)';
        }, $path);
        return ['#^' . $regex . '$#u', $params];
    }

    public function dispatch(Request $request): void
    {
        $method = $request->method();
        $uri    = $request->path();

        foreach ($this->routes[$method] ?? [] as $route) {
            if (!preg_match($route['regex'], $uri, $matches)) continue;
            array_shift($matches);
            $args = $route['params'] ? array_combine($route['params'], $matches) : [];

            foreach ($route['middlewares'] as $mw) {
                $this->container->get($mw)->handle($request);
            }

            [$class, $action] = $route['handler'];
            $html = $this->container->get($class)->$action($request, ...array_values($args));
            (new Response())->html($html)->send();
            return;
        }

        (new Response())
            ->status(404)
            ->html($this->container->get(View::class)->render('errors/404', ['lang' => 'uk']))
            ->send();
    }
}