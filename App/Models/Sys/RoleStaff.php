<?php
namespace Backend\Models\Sys;

use Backend\Models\Base;

class RoleStaff extends Base
{
    public function initialize()
    {

    }

    /**
     * @var int $id
     */
    public $id;

    public function getSource()
    {
        return 'auth_role_staff';
    }
}