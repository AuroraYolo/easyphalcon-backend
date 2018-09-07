<?php
namespace Backend\Services;

use Backend\Components\ErrorCode;
use Backend\Components\Exception\ApiException;
use Backend\Plugins\Verify;
use Backend\Services\User\AccountType;
use Backend\Services\User\EmailAccount;

class UserService
{
    use Verify;
    /**
     * @var AccountType[] Account types
     */
    protected $accountTypes;

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
            $accountType = 'phone';
        } else {
            $accountType = 'username';
        }
        return $accountType;
    }

    /**
     * @param string $accountType
     * @param array  $data
     *
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
    }

    public function login($accountType, array $data)
    {
    }

    public function registerAccountType($name, AccountType $accountType)
    {
        $this->accountTypes[$name] = $accountType;
        return $this;
    }

    public function getAccountTypes()
    {
        return $this->accountTypes;
    }

    public function getAccountType($name)
    {
        if (array_key_exists($name, $this->accountTypes)) {
            return $this->accountTypes[$name];
        }
        return null;
    }
}