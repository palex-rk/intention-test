<?php

namespace Src;

use App\Model\Task;

class Router 
{
    protected $controller;
    protected $method;
    protected $params;

    public function __construct()
    {
        $this->parseUrl();
        $this->dispach();
    }
    
    public function parseUrl()
    {
        $request = trim($_SERVER['REQUEST_URI'], '/'); // if (empty($request)... else)
        if (!empty($request)) {
            $url = explode('/', $request);
            $this->controller = isset($url[0]) ? 'App\Controller\\' . ucwords($url[0]) . 'Controller' : 'TaskController';
            $this->method = isset($url[1]) ? $url[1] : 'index';
            unset($url[0], $url[1]);
            $this->params = !empty($url) ? array_values($url) : [];
        } else {
            die('404');
        }
    }

    public function dispach()
    {
        if (class_exists($this->controller)) {
            // echo 'class exists -router';die;
            $this->controller = new $this->controller(new Model, new Task);die('prolazi');

            if (method_exists($this->controller, $this->method)) {
                //echo ' there is a method -router' . '<hr>';
                call_user_func_array([$this->controller, $this->method], $this->params);die;
            }

            call_user_func_array([$this->controller, 'index'], $this->params);
        }
  
    }
}