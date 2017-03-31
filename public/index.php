<?php
use Slim\Http\Request;
use Slim\Http\Response;

require '../vendor/autoload.php';

session_start();

$settings = ['settings' => [
    'displayErrorDetails' => true,
    ]];

$app = new \Slim\App($settings);

require '../app/container.php';

$container = $app->getContainer();

$app->add(new App\Middlewares\FlashMiddleware($container->view->getEnvironment()));
$app->add(new App\Middlewares\OldMiddleware($container->view->getEnvironment()));
$app->add(new App\Middlewares\TwigCsrfMiddleware($container->view->getEnvironment(), $container->csrf));
$app->add($container->csrf);

$app->get('/', 'App\Controllers\PagesController:home');

$app->get('/contact', 'App\Controllers\PagesController:getContact')->setName('contact');
$app->post('/contact', 'App\Controllers\PagesController:postContact');

$app->run();
