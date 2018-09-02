<?php
namespace Backend\Components\Core;

use Backend\Components\Acl\Access;
use Backend\Components\Auth\Jwt\Jwt;
use Backend\Components\Auth\Manager;
use Backend\Components\Http\Request;
use Phalcon\Http\Response;
use Phalcon\Mvc\User\Plugin;

/**
 * Class ApiPlugin
 * @package Backend\Components\Core
 * @property Request  $request
 * @property Response $response
 * @property Access   $acl
 * @property Jwt      $jwt
 * @property Manager  $authManager
 */
class ApiPlugin extends Plugin
{

}