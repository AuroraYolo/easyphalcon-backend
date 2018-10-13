<?php
namespace Backend\Config;

use Backend\Controllers\Sys\MenuController;
use Backend\Controllers\Sys\RoleController;
use Backend\Controllers\Sys\RoleStaffMapController;
use Backend\Controllers\Sys\SysManageController;
use Backend\Controllers\V1\SignController;
use Backend\Controllers\V1\UserController;
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
/**
 * @api 业务接口
 */
$router->addResource(substr(SignController::class, 0, -10), '/api/v1/sign');
$router->addResource(substr(UserController::class, 0, -10), '/api/v1/user');

/**
 * 系统管理服务
 */
$router->addResource(substr(MenuController::class, 0, -10), '/sys/menu');
$router->addResource(substr(RoleController::class, 0, -10), '/sys/role');
$router->addResource(substr(RoleStaffMapController::class, 0, -10), '/sys/roleStaffMap');
$router->addResource(substr(SysManageController::class, 0, -10), '/sys/sysManage');

return $router;

