<?php
namespace Backend\Components\Auth;

class Session
{

    /**
     * @var string Identity of the session
     */
    protected $_identity;

    /**
     * @var string Session Token
     */
    protected $_token;
    /**
     * @var int Session Token Start time
     */
    protected $_startTime;
    /**
     * @var int Session Token expiration time
     */
    protected $_expirationTime;

    public function __construct($identity, $startTime, $expirationTime, $token = null)
    {
        $this->_identity       = $identity;
        $this->_startTime      = $startTime;
        $this->_expirationTime = $expirationTime;
        $this->_token          = $token;
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