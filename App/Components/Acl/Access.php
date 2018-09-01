<?php
namespace Backend\Components\Acl;

use Phalcon\Acl;
use Phalcon\Acl\Resource;
use Phalcon\Acl\Role;
use Phalcon\Acl\Adapter\Memory as AclMemory;

/**
 * ACL权限配置
 * Class Access
 * @package Backend\Components\Acl
 */
class Access
{
    protected $publicResources = [
        'about'    => ['index'],
        'register' => ['index'],
        'errors'   => ['show404', 'show500'],
        'session'  => ['index', 'register', 'start', 'end'],
        'contact'  => ['index', 'send'],
    ];
    protected $privateResources = [
        'index' => ['edit']
    ];

    public function __construct()
    {
    }

    /**
     *
     *
     * @return AclMemory
     */
    public function getAcl()
    {
        $acl = new AclMemory();
        $acl->setDefaultAction(
            Acl::DENY
        );
        $roles = [
            'users'  => new Role('Users'),
            'guests' => new Role('Guests'),
        ];
        foreach ($roles as $role) {
            $acl->addRole($role);
        }
        foreach ($this->privateResources as $resourceName => $actions) {
            $acl->addResource(
                new Resource($resourceName),
                $actions
            );
        }
        foreach ($this->publicResources as $resourceName => $actions) {
            $acl->addResource(
                new Resource($resourceName),
                $actions
            );
        }
        foreach ($roles as $role) {
            foreach ($this->publicResources as $resource => $actions) {
                $acl->allow(
                    $role->getName(),
                    $resource,
                    '*'
                );
            }
        }
        foreach ($this->privateResources as $resource => $actions) {
            foreach ($actions as $action) {
                $acl->allow('Users', $resource, $action);
            }
        }
        return $acl;
    }

}