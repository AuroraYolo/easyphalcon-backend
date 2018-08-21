<?php
namespace Backend\Middleware;

use Backend\Components\Core\ApiPlugin;
use Phalcon\Events\Event;
use Phalcon\Exception;
use Phalcon\Mvc\Dispatcher;

class NotFoundMiddleWare extends ApiPlugin
{
    /**
     * @param Event      $event
     * @param Dispatcher $dispatcher
     *
     * @throws Exception
     */
    public function beforeNotFoundAction(Event $event, Dispatcher $dispatcher)
    {
        throw new Exception('Not Found');
    }
}