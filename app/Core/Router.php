<?php

namespace App\Core;

Use App\Core\Request;
Use App\Controllers\HomeController;

class Router
{

   private array $routes = [];
   private array $namedRoutes = [];
   private Request $request;

   public function __construct()
   {
        $routes = parse_ini_file('../app/routes.ini', true);
        foreach ($routes as $route) {
            $this->add($route['name'], $route['method'], $route['path'], $route['controller'], $route['action']);
        }
   }

    /**
     * @param string $name
     * @param string $method
     * @param string $path
     * @param string $controller
     * @param string $action
     * @return void
     */
    private function add(string $name, string $method, string $path, string $controller, string $action): void
   {
       $params = [];
       preg_match_all('/\{(\w+)\}/', $path, $matches);

       if (!empty($matches[1])) {
           $params = $matches[1];
       }

       $path = preg_replace('/\{(\w+)\}/', '([\w-]+)', $path);
       $this->routes[] = compact('name', 'method', 'path', 'controller', 'action', 'params');
       $this->namedRoutes[$name] = $path;
   }

   public function extractUri(): bool
   {

       $uri = $_GET['route'] ?? '/';

       $exist = false;

       foreach($this->routes as $route) {
           // if route exist
           if (preg_match("#^{$route['path']}$#", $uri, $matches)) {
               $exist = true;
               // if method is not the same
               if($route['method'] !== $_SERVER['REQUEST_METHOD']) {
                   http_response_code(405);
                   return false;
               }
                if($this->createRequest($route, $uri, $matches)) {
                    return true;
                }
           }
       }
       return false;
   }

   private function createRequest(array $route, string $uri, array $matches): bool
   {
        $request = new Request($route, $uri, $matches);
        $this->request = $request;
        return true;
   }

    /**
     * @return void
     */
    public function dispatch(): void
    {

        if(!$this->extractUri()) {
            http_response_code(404);
            return;
        }

        $request = $this->request;
        $controller = $request->getController();
        $action = $request->getAction();
        
        $controller = new $controller();
        $controller->$action($request);
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getPathByName(string $name): ?string
    {
        return $this->namedRoutes[$name] ?? null;
    }


}