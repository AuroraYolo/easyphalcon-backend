<?php
namespace Backend\Controllers\V1;

use Backend\Controllers\BaseController;

/**
 * Class IndexController
 * @package Backend\Controllers\V1
 * @RoutePrefix('/api/v1/index')
 */
class IndexController extends BaseController
{
    /**
     * @Route(
     *     '/edit',
     *     methods={'POST', 'PUT'},
     *     name='save-robot'
     * )
     */
    public function editAction()
    {
        echo __METHOD__;
    }
}