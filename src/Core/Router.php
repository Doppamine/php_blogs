<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    // This project will only have GET requests
    public function get(string $pattern, callable $handler): void
    {
        $this->routes[] = [
            'method' => 'GET',
            'regex' => '#^'.str_replace('\{id\}', '(\d+)', preg_quote($pattern, '#')).'$#',
            'handler' => $handler,
        ];
    }

    public function dispatch(string $method, string $path): string
    {
        $path = rtrim($path, '/');
        if ($path === '') {
            $path = '/';
        }

        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['regex'], $path, $matches)) {
                array_shift($matches);
                return ($route['handler'])(...array_map('intval', $matches));
            }
        }
        throw new NotFoundException("Route not found");
    }

}