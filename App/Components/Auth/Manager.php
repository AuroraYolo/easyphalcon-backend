<?php
namespace Backend\Components\Auth;

use Backend\Components\Core\ApiPlugin;
use Backend\Components\ErrorCode;
use Backend\Components\Exception\ApiException;

class Manager extends ApiPlugin
{
    /**
     * @var Session Current active session
     */
    protected $_session;

    public function getSession()
    {
        return $this->_session;
    }

    public function setSession(Session $session)
    {
        $this->_session = $session;
    }

    /**
     * 验证token
     *
     * @param $token
     *
     * @return bool
     * @throws ApiException
     */
    public function authenticateToken($token)
    {
        //Todo 从redis中查询是否存在,不存在return，从而来确保token的时效性
        try {
            $session = $this->jwt->getSession($token);
        } catch (\Exception $ex) {
            throw new ApiException(ErrorCode::AUTH_TOKEN_INVALID);
        }
        if (!$session) {
            return false;
        }
        if ($session->getExpirationTime() < time()) {
            throw new ApiException(ErrorCode::AUTH_SESSION_EXPIRED);
        }
        $session->setToken($token);
        if (!$session->getIdentity()) {
            throw new ApiException(ErrorCode::AUTH_TOKEN_INVALID);
        }
        $this->_session = $session;
        return true;
    }
}