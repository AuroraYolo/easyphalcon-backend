<?php
namespace Backend\Controllers\V1;

use Backend\Components\Auth\Jwt\Jwt;
use Backend\Components\Auth\Session;
use Backend\Components\ErrorCode;
use Backend\Components\Exception\ApiException;
use Backend\Controllers\BaseController;
use Backend\Services\UserService;

/**
 * Class UserController
 * @package Backend\Controllers\V1
 * @RoutePrefix('/api/v1/user')
 */
class UserController extends BaseController
{
    /**
     * @var UserService $userService
     */
    protected $userService;

    /**
     * @throws \ReflectionException
     */
    public function initialize()
    {
        $this->userService = new UserService();
    }

    /**
     * @Post(
     *     '/register'
     * )
     * @throws
     */
    public function registerAction()
    {
        $account  = $this->request->get('account');
        $password = $this->request->get('password');
        if (!$account || !$password) {
            throw new ApiException(ErrorCode::POST_DATA_NOT_PROVIDED, '账户或密码不能为空');
        }
        $accountType = $this->userService->accountTypeCheck($account);
        if (!$accountType) {
            throw new ApiException(ErrorCode::POST_DATA_INVALID, '账户类型错误');
        }
        $identity  = $this->userService->register($accountType, ['username' => $account, 'password' => $password]);
        $startTime = time();
        $session   = new Session($identity, $startTime, $startTime + 86400);
        /**
         * @var Jwt $jwt
         */
        $token = $this->jwt->getToken($session);
        $session->setToken($token);
        return [
            'token'   => $token,
            'expires' => $session->getExpirationTime(),
            'user'    => $session->getIdentity()
        ];
    }

}