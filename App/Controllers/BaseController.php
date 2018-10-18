<?php
namespace Backend\Controllers;

use Backend\Components\Auth\Jwt\Jwt;
use Backend\Components\Http\Request;
use Backend\Components\Http\Response;
use Backend\Components\Validation\Validation;
use Phalcon\Mvc\Controller;

/**
 * Class BaseController
 * @package Backend\Controllers
 * @property Response $response
 * @property Jwt      $jwt
 * @property Request  $request
 * @property Validation $validation
 */
class BaseController extends Controller
{
    public function initialize()
    {
    }

}