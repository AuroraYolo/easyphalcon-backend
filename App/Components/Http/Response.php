<?php
namespace Backend\Components\Http;

use Backend\Components\Exception\ApiException;
use Backend\Constant\Services;
use Phalcon\Mvc\Dispatcher;

class Response extends \Phalcon\Http\Response
{
    public function setErrorContent(\Throwable $throwable, $developers = false)
    {
        /**
         * @var Request $request
         */
        $request = $this->getDI()->getShared(Services::REQUEST);
        /**
         * @var Dispatcher $dispatcher
         */
        $dispatcher = $this->getDI()->getShared(Services::DISPATCHER);
        /**
         * @var ErrorHelper $errorHelper
         */
        $errorHelper = $this->getDI()->getShared(Services::ERROR_HELPER);
        $errorCode   = $throwable->getCode();
        $errorMsg    = $throwable->getMessage();
        $statusCode  = 500;
        if ($errorHelper && $errorHelper->has($errorCode)) {
            $entityMsg  = $errorHelper->get($errorCode);
            $statusCode = $entityMsg['statusCode'];
            if (!$errorMsg) {
                $errorMsg = $entityMsg['message'];
            }
        }
        $errorInfo = [
            'code'    => $errorCode,
            'message' => $errorMsg
        ];
        if ($throwable instanceof ApiException && $throwable->getUserInfo() != null) {
            $errorInfo['details'] = $throwable->getUserInfo();
        }
        if ($developers === true) {
            $developersResponse = [
                'file'           => $throwable->getFile(),
                'line'           => $throwable->getLine(),
                'controller'     => $dispatcher->getControllerName(),
                'action'         => $dispatcher->getActionName(),
                'request_method' => $request->getMethod(),
                'params'         => $request->getPostedData()
            ];
            if ($throwable instanceof ApiException && $throwable->getDeveloperInfo() != null) {
                $developersResponse['info'] = $throwable->getDeveloperInfo();
            }
            $errorInfo['developers'] = $developersResponse;
        }
        $this->setJsonContent(['error' => $errorInfo]);
        $this->setStatusCode($statusCode);
    }

    public function setJsonContent($content, $jsonOptions = 0, $depth = 512)
    {
        parent::setJsonContent($content, $jsonOptions, $depth);
        $this->setContentType('application/json', 'UTF_8');
        $this->setEtag(md5($this->getContent()));
    }
}