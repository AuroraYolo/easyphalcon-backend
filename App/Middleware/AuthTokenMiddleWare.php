<?php
namespace Backend\Middleware;

use Backend\Components\Core\ApiPlugin;
use Phalcon\Dispatcher;
use Phalcon\Events\Event;

class AuthTokenMiddleWare extends ApiPlugin
{
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        $annotations = $this->annotations->getMethod(
            $dispatcher->getHandlerClass(),
            $dispatcher->getActiveMethod()
        );
        foreach ($annotations as $annotation){

        }
    }
}