<?php

namespace KaayDem\Core;

class Router
{
    private array $routes = [];
    private string $basePath = '';
    
    public function __construct(string $basePath = '')
    {
        $this->basePath = rtrim($basePath, '/');
    }
    
    public function addRoute(string $method, string $path, array $handler): void
    {
        $path = $this->basePath . $path;
        $this->routes[$method][$path] = $handler;
    }
    
    public function dispatch(string $method, string $uri): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';
        
        if (strpos($uri, $this->basePath) === 0 && $this->basePath !== '') {
            $uri = substr($uri, strlen($this->basePath)) ?: '/';
        }
        
        if ($uri === '') {
            $uri = '/';
        }
        
        if (!isset($this->routes[$method])) {
            $this->notFound();
            return;
        }
        
        $matchedRoute = null;
        $params = [];
        
        // Chercher la route complète
        $fullRoute = '/kaay_dem' . $uri;
        if (isset($this->routes[$method][$fullRoute])) {
            $matchedRoute = $this->routes[$method][$fullRoute];
        }
        
        if ($matchedRoute === null) {
            foreach ($this->routes[$method] as $route => $handler) {
                if ($route === $uri) {
                    $matchedRoute = $handler;
                    break;
                }
                
                $pattern = preg_replace('/\{[a-zA-Z]+\}/', '([a-zA-Z0-9-]+)', $route);
                $pattern = str_replace('/', '\/', $pattern);
                $pattern = '/^' . $pattern . '$/';
                
                if (preg_match($pattern, $uri, $matches)) {
                    array_shift($matches);
                    $matchedRoute = $handler;
                    $params = $matches;
                    break;
                }
            }
        }
        
        if ($matchedRoute === null) {
            $this->notFound();
            return;
        }
        
        [$controllerClass, $action] = $matchedRoute;
        $controller = new $controllerClass();
        
        if (!method_exists($controller, $action)) {
            throw new \Exception("Méthode $action introuvable");
        }
        
        $controller->$action(...$params);
    }
    
    private function notFound(): void
    {
        http_response_code(404);
        echo "404 - Page non trouvée";
        exit;
    }
}