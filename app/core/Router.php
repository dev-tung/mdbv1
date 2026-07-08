<?php

class Router
{
    protected static array $routes = [];

    // =========================
    // REGISTER GET
    // =========================
    public static function get(string $uri, string $handler, array $middleware = []): void
    {
        self::$routes['GET'][] = [
            'uri' => $uri,
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }

    // =========================
    // REGISTER POST
    // =========================
    public static function post(string $uri, string $handler, array $middleware = []): void
    {
        self::$routes['POST'][] = [
            'uri' => $uri,
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }

    // =========================
    // DISPATCH
    // =========================
    public static function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);

        foreach (self::$routes[$method] ?? [] as $route) {
            $pattern = self::convertUriToRegex($route['uri']);

            if (!preg_match($pattern, $uri, $matches)) {
                continue;
            }

            array_shift($matches);

            // resolve controller file
            $controllerFile = self::resolveControllerFile($route['handler']);

            if (!$controllerFile) {
                die("Controller not found: {$route['handler']}");
            }

            // detect module
            $module = self::detectModuleFromPath($controllerFile);

            // set view module
            View::setModule($module);

            // middleware
            Middleware::handle($route['middleware'] ?? []);

            // controller action
            self::callAction($route['handler'], $controllerFile, $matches);

            return;
        }

        http_response_code(404);

        echo "404 NOT FOUND: {$uri}";
    }

    // =========================
    // CONVERT ROUTE PARAMS
    // =========================
    protected static function convertUriToRegex(string $uri): string
    {
        $pattern = preg_replace_callback('#\{([a-zA-Z_]+)\}#', fn() => '([a-zA-Z0-9_-]+)', $uri);

        return "#^{$pattern}$#";
    }

    // =========================
    // CALL ACTION
    // =========================
    protected static function callAction(string $handler, string $file, array $params = []): void
    {
        [$controller, $action] = explode('@', $handler);

        require_once $file;

        if (!class_exists($controller)) {
            die("Class not found: {$controller}");
        }

        $instance = new $controller();

        if (!method_exists($instance, $action)) {
            die("Method not found: {$controller}@{$action}");
        }

        call_user_func_array([$instance, $action], $params);
    }

    // =========================
    // RESOLVE CONTROLLER FILE
    // =========================
    protected static function resolveControllerFile(string $handler): ?string
    {
        [$controller] = explode('@', $handler);

        $modules = glob(BASE_PATH . '/app/modules/*', GLOB_ONLYDIR);

        foreach ($modules as $module) {
            $paths = [$module . "/controllers/{$controller}.php", $module . "/endpoints/{$controller}.php"];

            foreach ($paths as $path) {
                if (file_exists($path)) {
                    return $path;
                }
            }
        }

        return null;
    }

    // =========================
    // DETECT MODULE
    // =========================
    protected static function detectModuleFromPath(string $file): string
    {
        $parts = explode('/modules/', $file);

        $sub = explode('/', $parts[1] ?? '');

        return $sub[0] ?? 'website';
    }
}
