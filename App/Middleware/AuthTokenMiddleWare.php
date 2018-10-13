<?php
namespace Backend\Middleware;

use Backend\Components\Core\ApiPlugin;
use Phalcon\Dispatcher;
use Phalcon\Events\Event;

class AuthTokenMiddleWare extends ApiPlugin
{
    /**
     * @param Event      $event
     * @param Dispatcher $dispatcher
     *
     * @return bool
     * @throws \Backend\Components\Exception\ApiException
     */
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        //先判断是否携带token  判断token的过期时间 验证token
        $token = $this->request->getToken();
        if ($token) {
            return $this->authManager->authenticateToken($token);
        }
        return false;
    }
}