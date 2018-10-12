<?php
namespace Backend\Services;

use Backend\Components\Auth\Jwt\Jwt;
use Backend\Components\Auth\Session;
use Backend\Components\Core\ApiPlugin;
use Backend\Components\ErrorCode;
use Backend\Components\Exception\ApiException;
use Backend\Models\User;
use Backend\Plugins\Tools;
use Backend\Plugins\Verify;
use Backend\Services\User\AccountType;
use Backend\Services\User\EmailAccount;
use Backend\Services\User\PhoneAccount;
use Backend\Services\User\UsernameAccount;

class UserService
{
    use Verify;
    use Tools;
    /**
     * @var AccountType[] Account types
     */
    protected $accountTypes = [
    ];

    /**
     * UserService constructor.
     * @throws \ReflectionException
     */
    public function __construct()
    {
        $this->accountTypes = [
            EmailAccount::ACCOUNT_NAME    => EmailAccount::getInstance(),
            UsernameAccount::ACCOUNT_NAME => UsernameAccount::getInstance(),
            PhoneAccount::ACCOUNT_NAME    => PhoneAccount::getInstance(),
        ];
    }

    /**
     * 校验账户类型
     *
     * @param $username
     *
     * @return string
     */
    public function accountTypeCheck($username) : string
    {
        if ($this->isEmail($username)) {
            $accountType = EmailAccount::ACCOUNT_NAME;
        } elseif ($this->isMobile($username)) {
            $accountType = PhoneAccount::ACCOUNT_NAME;
        } else {
            $accountType = UsernameAccount::ACCOUNT_NAME;
        }
        return $accountType;
    }

    /**
     * 注册
     *
     * @param       $accountType
     * @param array $data
     *
     * @return string
     * @throws ApiException
     */
    public function register($accountType, array $data)
    {
        if (!$account = $this->getAccountType($accountType)) {
            throw new ApiException(ErrorCode::AUTH_INVALID_ACCOUNT_TYPE);
        }
        $identity = $account->register($data);
        if (!$identity) {
            throw new ApiException(ErrorCode::AUTH_LOGIN_FAILED);
        }
        return $identity;
    }

    /**
     * 登录服务
     *
     * @param       $accountType
     * @param array $data
     *
     * @return string
     * @throws ApiException
     */
    public function login($accountType, array $data)
    {
        if (!$account = $this->getAccountType($accountType)) {
            throw new ApiException(ErrorCode::AUTH_INVALID_ACCOUNT_TYPE);
        }
        if (!$data) {
            throw new ApiException(ErrorCode::DATA_NOT_FOUND);
        }
        $identity = $account->login($data);
        if (!$identity) {
            throw new ApiException(ErrorCode::AUTH_LOGIN_FAILED);
        }
        return $identity;
    }

    public function registerAccountType($name, AccountType $accountType)
    {
        $this->accountTypes[$name] = $accountType::getInstance();
        return $this;
    }

    public function getAccountTypes()
    {
        return $this->accountTypes;
    }

    public function getAccountType($name) : ?AccountType
    {
        if (array_key_exists($name, $this->accountTypes)) {
            return $this->accountTypes[$name];
        }
        return null;
    }
}