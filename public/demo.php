<?php
use Slim\Http\Request;
use Slim\Http\Response;

require '../vendor/autoload.php';

class DemoMiddleware {

    public function __invoke(Request $request, Response $response, $next) {
        $response->write('<h1>Welcome</h1>');
        $response = $next($request, $response);
        $response->write('<h1>Goodbye</h1>');
        return $response;
    }

}

class Database {

    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function query($sql) {
        $req = $this->pdo->prepare($sql);
        return $req->fetchAll();
    }

}

class PagesController {

    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function hello(Request $request, Response $response, $args) {
        $posts = $this->container->db->query('SELECT * FROM posts');
        return $response->write('Hello ' . $args['name']);
    }

}

$app = new \Slim\App();
$container = $app->getContainer();

$container['pdo'] = function() {
    $pdo = new PDO('mysql:dbname=icaros-slim;host=localhost', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
};

$container['db'] = function($container) {
    return new Database($container->pdo);
};

$app->add(new DemoMiddleware());

$app->get('/', function(Request $request, Response $response) {
    $params = $request->getParams();
    //return $response->getBody()->write('Hello');
    return $response->write('Hello');
});

$app->get('/hello/{name}', 'PagesController:hello');

/*
$app->get('/hello/{name}', function(Request $request, Response $response, $args) {
    /*
    $req = $this->pdo->prepare('SELECT * FROM posts');
    //$req = $this->get('pdo')->prepare('SELECT * FROM posts');
    $req->execute();
    $posts = $req->fetchAll(PDO::FETCH_OBJ);
    */
   /*
    $posts = $this->db->query('SELECT * FROM posts');
    return $response->write('Hello ' . $args['name']);
});
*/

$app->run();
