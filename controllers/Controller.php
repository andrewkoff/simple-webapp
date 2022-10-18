<?php

class Controller {

    protected $app;
    protected $controller;
    protected $model;
    protected $view;
    protected $request;
    protected $uri;
    protected $action;
    protected $config;
    protected $allowedActions = [];
    protected $payload = [];

    function __construct($param) {
        $this->app = $param['app'];
        $this->controller = $param['controller'];
        if (class_exists($param['model'])) {
            $this->model = (new $param['model']($this->app->database))->init();
        }
        $this->config = $param['config'];
        $this->view = $param['view'];
        $this->request = $param['request'];
        $this->uri = $param['uri'];
        if ($this->request->requestMethod == 'POST') {
            $this->payload = $param['payload'];
        }
        $this->action = $this->request->query['action'] ?? 'index';
    }

    function run() {
        if (in_array($this->action, $this->allowedActions)) {
            $this->{'action' . $this->action}();
        } else {
            self::actionError(804);
        }
    }

    function redirect($page) {
        header("Location: $page");
    }

    function redirectToControllerIndex() {
        $this->redirect($this->uri);
    }

    function render($view, $params, $layout = 'main') {
        global $config;
        $file = VIEWS_DIR . strtolower($this->view) . DIRECTORY_SEPARATOR . $view . '.php';
        if (file_exists($file)) {
            $content = self::renderPhpFile($file, $params);
            extract($params, EXTR_OVERWRITE);
            $layout_file = VIEWS_DIR . 'layouts' . DIRECTORY_SEPARATOR . $layout . '.php';
            require $layout_file;
        } else {
            $this->actionError(803);
        }
    }

    static function renderPhpFile($file, $params = []) {
        ob_start();
        ob_implicit_flush(false);
        extract($params, EXTR_OVERWRITE);
        require($file);
        return ob_get_clean();
    }

    static function actionError($error_code) {
        global $config;
        $params = ['error_code' => $error_code, 'error_message' => $config['errors'][$error_code]];
        self::renderError($params);
    }

    static function renderError($params) {
        global $config;
        $file = VIEWS_DIR . 'site' . DIRECTORY_SEPARATOR . 'error.php';
        $content = self::renderPhpFile($file, $params);
        $layout_file = VIEWS_DIR . 'layouts' . DIRECTORY_SEPARATOR . 'main.php';

        require $layout_file;
    }

}
