<?php
namespace Backend\Middleware;

use Backend\Components\ErrorCode;
use Backend\Components\Exception\ApiException;
use Backend\Components\Core\ApiPlugin;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;

class NotFoundMiddleWare extends ApiPlugin
{
    /**
     * @param Event      $event
     * @param Dispatcher $dispatcher
     *
     * @throws ApiException
     */
    public function beforeNotFoundAction(Event $event, Dispatcher $dispatcher)
    {
        throw new ApiException(ErrorCode::GENERAL_NOT_FOUND);
    }
}