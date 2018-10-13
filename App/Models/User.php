<?php
namespace Backend\Models;

class User extends Base
{
    public function initialize()
    {

    }

    /**
     * @var int $id
     */
    public $id;
    /**
     * @var string $email
     */
    public $email;
    /**
     * @var string $phone
     */
    public $phone;
    /**
     * @var string $username
     */
    public $username;

    public function getSource()
    {
        return 'account_user';
    }

    /**
     * 校验密码
     *
     * @param $password
     *
     * @return bool
     */
    public function verifyPassWord($password)
    {
        return true;
    }
}