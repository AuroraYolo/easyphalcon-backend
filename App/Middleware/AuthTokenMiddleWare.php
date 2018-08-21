<?php
namespace Backend\Middleware;

use Backend\Components\Core\ApiPlugin;
use Phalcon\Dispatcher;
use Phalcon\Events\Event;

class AuthTokenMiddleWare extends ApiPlugin
{
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
    }
}