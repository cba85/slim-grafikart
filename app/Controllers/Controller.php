<?php

namespace App\Controllers;
use Psr\Http\Message\ResponseInterface;

class Controller {

    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function render(ResponseInterface $response, $filename, $params = []) {
        return $this->container->view->render($response, $filename, $params);
    }

    public function redirect($response, $route, $status = 302) {
        return $response->withStatus($status)->withHeader('Location', $this->router->pathFor($route));
    }

    public function flash($message, $type = "success") {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        return $_SESSION['flash'][$type] = $message;
    }

    public function __get($name) {
        return $this->container->$name;
    }

}
