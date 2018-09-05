<?php
namespace Backend\Controllers\V1;

use Backend\Controllers\BaseController;

/**
 * Class ApiController
 * @package Backend\Controllers\V1
 * @RoutePrefix('/api/v1/api')
 */
class ApiController extends BaseController
{
    /**
     * @Get(
     *     '/index'
     * )
     */
    public function indexAction()
    {
        echo __METHOD__;
    }

    /**
     * @Post(
     *     '/list'
     * )
     */
    public function listAction(){
        echo __METHOD__;
    }

}