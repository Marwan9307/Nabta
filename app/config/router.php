<?php

class Router {
    private $routes = [];

    public function get($path, $handler) {
        $this->routes['GET'][$path] = $handler;
    }

    public function post($path, $handler) {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch($method, $uri) {
        $uri = strtok($uri, '?');
        $uri = rtrim($uri, '/') ?: '/';

        if (isset($this->routes[$method][$uri])) {
            return $this->call($this->routes[$method][$uri]);
        }

        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $pattern = preg_replace('#\{(\w+)\}#', '(\w+)', $route);
            if (preg_match("#^{$pattern}$#", $uri, $matches)) {
                array_shift($matches);
                return $this->call($handler, $matches);
            }
        }

        http_response_code(404);
        echo '<h1>404 Not Found</h1>';
    }

    private function call($handler, $params = []) {
        [$class, $method] = explode('@', $handler);
        $file = __DIR__ . '/../controllers/' . $class . '.php';
        if (!file_exists($file)) {
            http_response_code(500);
            echo "Controller not found: $class";
            return;
        }
        require_once $file;
        $controller = new $class();
        call_user_func_array([$controller, $method], $params);
    }
}

