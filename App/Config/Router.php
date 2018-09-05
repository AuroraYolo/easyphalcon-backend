<?php
namespace Backend\Config;

use Backend\Controllers\V1\ApiController;
use Backend\Controllers\V1\IndexController;
use Backend\Controllers\V1\SignController;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\Router\Annotations as RouterAnnotations;

/**
 * 开启注解路由
 */
$router = new RouterAnnotations(false);

$router->setUriSource(Router::URI_SOURCE_GET_URL);
$router->setUriSource(Router::URI_SOURCE_SERVER_REQUEST_URI);
$router->setDefaultNamespace('Backend\Controllers');
$router->setDefaultController('index');
$router->setDefaultAction('index');
$router->removeExtraSlashes(true);
$router->add('/', [
    'controller' => 'index',
    'action'     => 'index'
]);
$router->addResource(substr(ApiController::class, 0, -10), '/api/v1/api');
$router->addResource(substr(IndexController::class, 0, -10), '/api/v1/index');
$router->addResource(substr(SignController::class, 0, -10), '/api/v1/sign');
return $router;

