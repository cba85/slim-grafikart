<?php

namespace App\Middlewares;

Class OldMiddleware {

    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function __invoke($request, $response, $next) {
        $this->twig->addGlobal('old', isset($_SESSION['old']) ? $_SESSION['old'] : []);
        if (isset($_SESSION['old'])) {
            unset($_SESSION['old']);
        }
        $response = $next($request, $response);
        if ($response->getStatusCode() === 400) {
            $_SESSION['old'] = $request->getParams();
        }
        return $response;
    }

}
