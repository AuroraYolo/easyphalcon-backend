<?php
namespace Backend\Components\Http;

use Backend\Components\PostedDataMethods;

class Request extends \Phalcon\Http\Request
{
    protected $_postedDataMethod = PostedDataMethods::AUTO;

    /**
     * @param string $method One of the method constants defined in PostedDataMethods
     *
     * @return $this
     */
    public function postedDataMethod($method)
    {
        $this->_postedDataMethod = $method;
        return $this;
    }

    /**
     * @return string $method One of the method constants defined in PostedDataMethods
     */
    public function getPostedDataMethod()
    {
        return $this->_postedDataMethod;
    }

    /**
     * @param null $httpMethod
     *
     * @return array|bool|mixed|\stdClass
     */
    public function getPostedData($httpMethod = null)
    {
        $method = $httpMethod !== null ? $httpMethod : $this->_postedDataMethod;
        if ($method == PostedDataMethods::AUTO) {
            if ($this->getContentType() === 'application/json') {
                $method = PostedDataMethods::JSON_BODY;
            } elseif ($this->isPost()) {
                $method = PostedDataMethods::POST;
            } elseif ($this->isGet()) {
                $method = PostedDataMethods::GET;
            } elseif ($this->isPut()) {
                $method = PostedDataMethods::PUT;
            }
        }
        if ($method == PostedDataMethods::JSON_BODY) {
            return $this->getJsonRawBody();
        } elseif ($method == PostedDataMethods::POST) {
            return $this->getPost();
        } elseif ($method == PostedDataMethods::PUT) {
            return $this->getPut();
        } elseif ($method == PostedDataMethods::GET) {
            return $this->getQuery();
        }
        return [];
    }

    /**
     * @return null|string
     */
    public function getToken()
    {
        $authToken = $this->getHeader('token');
        return $authToken ?? null;
    }

}