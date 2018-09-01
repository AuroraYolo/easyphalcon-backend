<?php
namespace Backend\Components\Core;

use Backend\Components\Acl\Access;
use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Phalcon\Mvc\User\Plugin;

/**
 * Class ApiPlugin
 * @package Backend\Components\Core
 * @property Request  $request
 * @property Response $response
 * @property Access      $acl
 */
class ApiPlugin extends Plugin
{

}