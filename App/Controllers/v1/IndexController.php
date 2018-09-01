<?php
namespace Backend\Controllers\V1;

use Backend\Controllers\BaseController;

/**
 * Class IndexController
 * @package Backend\Controllers\V1
 * @RoutePrefix('/api/v1/index')
 * @group(path='/index')
 */
class IndexController extends BaseController
{
    /**
     * @Route(
     *     '/edit',
     *     methods={'POST', 'PUT'},
     *     name='save-robot'
     * )
     * @point(scope={public,private},index='/index')
     */
    public function editAction()
    {
        echo __METHOD__;
    }
}