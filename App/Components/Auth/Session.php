<?php
namespace Backend\Component\Auth;

class Session
{

    /**
     * @var string Identity of the session
     */
    protected $_identity;

    /**
     * @var string Account-type name of the session
     */
    protected $_accountTypeName;

    /**
     * @var string Session Token
     */
    protected $_token;
    protected $_startTime;
    protected $_expirationTime;

    public function __construct($accountTypeName, $identity, $startTime, $expirationTime, $token = null)
    {
        $this->_accountTypeName = $accountTypeName;
        $this->_identity        = $identity;
        $this->_startTime       = $startTime;
        $this->_expirationTime  = $expirationTime;
        $this->_token           = $token;
    }

    public function getIdentity()
    {
        return $this->_identity;
    }

    public function getToken()
    {
        return $this->_token;
    }

    public function setToken($token)
    {
        $this->_token = $token;
    }

    public function getExpirationTime()
    {
        return $this->_expirationTime;
    }

    public function setExpirationTime($time)
    {
        $this->_expirationTime = $time;
    }

    public function getStartTime()
    {
        return $this->_startTime;
    }

    public function setStartTime($time)
    {
        $this->_startTime = $time;
    }

}