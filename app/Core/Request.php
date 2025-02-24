<?php

namespace App\Core;



class Request
{


    private $uri;
    private $method;
    private array $get = [];
    private array $post = [];
    private $controller;
    private $action;




    /**
     * @param $route
     * @param $uri
     * @param $matches
     */
    public function __construct($route, $uri, $matches)
    {

        // $_GET Var
        $get = [];
        foreach($route['params'] as $k=>$value) {
            $get[$value] = $matches[$k+1];
        }

        // classique $_POST
        foreach($_POST as $key => $value) {
            $this->post[$key] = $value;
        }

        // JSON data
        $input = file_get_contents("php://input");
        $json = json_decode($input, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            $this->post = array_merge($this->post, $json); // Fusionne POST normal et JSON
        }

        $this->method = $route['method'];
        $this->uri = $uri;
        $this->get = $get;
        $this->controller = $route['controller'];
        $this->action = $route['action'];
    }

    /**
     * @param $key
     * @return ?string
     */
    public function param($key): ?string
    {
        $result = null;

        // Si la clé existe dans le tableau GET
        if(array_key_exists($key, $this->get)) {
            $result = $this->get[$key];
        }

        // Si la clé existe dans le tableau POST
        if(array_key_exists($key, $this->post)) {
            $result = $this->post[$key];
        }

        return $result;
    }

    /**
     * @return array
     */
    public function params(): array
    {
        return array_merge($this->get, $this->post);
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getGet(): array
    {
        return $this->get;
    }

    /**
     * @return array
     */
    public function getPost(): array
    {
        return $this->post;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }



}