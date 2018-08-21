<?php
namespace Backend\Controllers;

/**
 * Class IndexController
 * @package Backend\Controllers
 * @group(path='/me')
 */
class IndexController extends BaseController
{
    public function initialize()
    {
    }

    /**
     * @point(scope={public,private},me='/me')
     */
    public function errorAction()
    {

    }

}