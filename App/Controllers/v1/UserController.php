<?php
namespace Backend\Controllers\V1;

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

    public function initialize()
    {
        $this->userService = new UserService();

    }

    /**
     * @Post(
     *     '/register'
     * )
     */
    public function registerAction()
    {
        $account  = $this->request->getPost('account');
        $password = $this->request->getPost('password');
        if (!$account || !$password) {
            throw new ApiException(ErrorCode::POST_DATA_NOT_PROVIDED, '账户或密码不能为空');
        }
        $accountType = $this->userService->accountTypeCheck($account);

    }
}