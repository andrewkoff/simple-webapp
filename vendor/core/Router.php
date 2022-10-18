<?php

class Router {

    private $request;
    private $supportedHttpMethods = array(
        "GET",
        "POST"
    );

    function __construct(IRequest $request) {
        $this->request = $request;
    }

    function __call($name, $args) {
        list($route, $method) = $args;

        if (!in_array(strtoupper($name), $this->supportedHttpMethods)) {
            $this->invalidMethodHandler();
        }

        $this->{strtolower($name)}[$this->formatRoute($route)] = $method;
    }

    /**
     * Removes trailing forward slashes from the right of the route.
     * @param route (string)
     */
    private function formatRoute($route) {
        $result = rtrim($route, '/');
        if ($result === '') {
            return '/';
        }
        return $result;
    }

    private function invalidMethodHandler() {
        header("{$this->request->serverProtocol} 405 Method Not Allowed");
        $this->renderError(405);
    }

    private function defaultRequestHandler() {
        header("{$this->request->serverProtocol} 404 Not Found");
        $this->renderError(404);
    }

    private function renderError($error_code) {
        Controller::actionError($error_code);
    }

    /**
     * Resolves a route
     */
    function resolve() {
        $methodDictionary = $this->{strtolower($this->request->requestMethod)};

        $uri = $this->request->requestUri;
        $uriParsed = parse_url($uri);
        if (isset($uriParsed['query'])) {
            parse_str($uriParsed['query'], $query);
        } else
            $query = '';

        $this->request->{'uri'} = $uriParsed['path'];
        $this->request->{'query'} = $query;

        $formatedRoute = $this->formatRoute($uriParsed['path']);
        $method = $methodDictionary[$formatedRoute];

        if (is_null($method)) {
            $this->defaultRequestHandler();
            return;
        }
        echo call_user_func_array($method, array($this->request));
    }

    function __destruct() {
        $this->resolve();
    }

}
