<?php
use Medoo\Medoo;

class App {

    private $router;
    private $routes;
    public $database;
    private $config;
    private $controller;

    private function formatName($name, $suffix = '') {
        return ucfirst(strtolower($name)) . ucfirst(strtolower($suffix));
    }

    function __construct($config) {
        $this->config = $config;
        $this->router = new Router(new Request);
        $this->routes = $config['routes'];
        $this->database = new Medoo($config['connection']);
    }

    function run() {
        foreach ($this->routes as $route) {
            $this->router->{$route["method"]}($route['route'], function($request) {
                $param = [];
                $uri = ltrim($request->uri, '/');
                $uri = ($uri == '') ? 'index' : $uri;
                
                if ($request->requestMethod == 'POST') {
                    $param["payload"] = json_encode($request->getBody());
                }

                $param['config'] = $this->config;
                $param['request'] = $request;
                $param["uri"] = $uri;
                $param['controller'] = $this->formatName($uri, 'controller');
                $param['model'] = $this->formatName($uri);
                $param['view'] = $this->formatName($uri);
                $param['app'] = $this;

                if (class_exists($param['controller'])) {
                    $this->controller = new $param['controller']($param);
                    return $this->controller->run();
                } else {
                    Controller::actionError(801);
                }
            });
        }
    }

}
