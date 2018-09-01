<?php
namespace Backend\Middleware;

use Backend\Components\Core\ApiPlugin;
use Backend\Constant\Map;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;

class AclMiddleWare extends ApiPlugin
{
    const POINT = 'point';

    protected $_matchedRouteNameParts = null;

    public function getMatchedPoint()
    {
        $point = $this->getMatchedRouteNamePart(self::POINT);
        return $point;
    }

    /**
     * 获取匹配的注解内容
     *
     * @param $key
     *
     * @return mixed|null
     */
    protected function getMatchedRouteNamePart($key)
    {
        if (is_null($this->_matchedRouteNameParts)) {
            $routeName = $this->annotations->getMethod($this->dispatcher->getHandlerClass(), $this->dispatcher->getActiveMethod())->getAnnotations();
            if (!$routeName) {
                return null;
            }
            $this->_matchedRouteNameParts = $routeName;
            if (is_array($this->_matchedRouteNameParts) && count($this->_matchedRouteNameParts) > 0) {
                foreach ($this->_matchedRouteNameParts as $matchedRouteNamePart) {
                    if ($matchedRouteNamePart->getName() === $key) {
                        $this->_matchedRouteNameParts[self::POINT] = $matchedRouteNamePart->getArguments();
                    }
                }
                return $this->_matchedRouteNameParts[self::POINT];
            }
            return null;
        }
    }

    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        $allowed     = false;
        $data        = $this->getMatchedPoint();
        $pointScopes = $data[Map::SCOPES];
        if (empty($pointScopes)) { //如果point 没有配置scopes,则公开访问
            return true;
        }
    }
}