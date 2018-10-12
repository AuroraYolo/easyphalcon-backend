<?php
namespace Backend\Services\User;

use Backend\Components\ErrorCode;
use Backend\Components\Exception\ApiException;
use Backend\Models\User;

class UsernameAccount implements AccountType
{

    const ACCOUNT_NAME = 'username';
    const PASSWORD = 'password';

    public function authenticate($identity)
    {
        // TODO: Implement authenticate() method.
    }

    /**
     * 用户注册
     *
     * @param array $data
     *
     * @return int|null|string
     * @throws ApiException
     */
    public function register($data)
    {
        $user = User::findFirst([
            'conditions' => 'username = :username:',
            'bind'       => [
                'username' => $data[self::ACCOUNT_NAME]
            ]
        ]);
        if ($user) {
            throw new ApiException(ErrorCode::ACCOUNT_EXISTS, '账户已存在');
        }
        $user = new User();
        $ret  = $user->save([
            'username' => $data[self::ACCOUNT_NAME],
            'password' => $data[self::PASSWORD]
        ]);
        if ($ret !== false) {
            return (string)$user->id;
        }
        return null;
    }

    /**
     * 登录
     *
     * @param array $data
     *
     * @return null|string
     */
    public function login($data)
    {
        /**
         * @var User $user
         */
        $user = User::findFirst([
            'conditions' => 'username = :username:',
            'bind'       => [
                'username' => $data[self::ACCOUNT_NAME]
            ]
        ]);
        if (!$user) {
            return null;
        }
        if (!$user->verifyPassWord($data[self::PASSWORD])) {
            return null;
        };
        return (string)$user->id;
    }

    /**
     * @return object
     * @throws \ReflectionException
     */
    public static function getInstance()
    {
        // TODO: Implement getInstance() method.
        $reflector = new \ReflectionClass(UsernameAccount::class);
        return $reflector->newInstance();
    }
}