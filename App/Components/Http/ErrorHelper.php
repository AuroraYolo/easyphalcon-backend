<?php
namespace Backend\Components\Http;

use Backend\Components\ErrorCode;

class ErrorHelper
{
    protected $errors = [

        // General
        ErrorCode::GENERAL_SYSTEM => [
            'statusCode' => 500,
            'message'    => 'General: System Error'
        ],

        ErrorCode::GENERAL_NOT_IMPLEMENTED => [
            'statusCode' => 500,
            'message'    => 'General: Not Implemented'
        ],

        ErrorCode::GENERAL_NOT_FOUND         => [
            'statusCode' => 404,
            'message'    => 'General: Not Found'
        ],

        // Authentication
        ErrorCode::AUTH_INVALID_ACCOUNT_TYPE => [
            'statusCode' => 400,
            'message'    => 'Authentication: Invalid Account Type'
        ],

        ErrorCode::AUTH_LOGIN_FAILED => [
            'statusCode' => 401,
            'message'    => 'Authentication: Login Failed'
        ],

        ErrorCode::AUTH_TOKEN_INVALID => [
            'statusCode' => 401,
            'message'    => 'Authentication: Login Failed'
        ],

        ErrorCode::AUTH_SESSION_EXPIRED => [
            'statusCode' => 401,
            'message'    => 'Authentication: Session Expired'
        ],

        ErrorCode::AUTH_SESSION_INVALID => [
            'statusCode' => 401,
            'message'    => 'Authentication: Session Invalid'
        ],

        // Access Control
        ErrorCode::ACCESS_DENIED        => [
            'statusCode' => 403,
            'message'    => 'Access: Denied'
        ],

        // Data
        ErrorCode::DATA_FAILED          => [
            'statusCode' => 500,
            'message'    => 'Data: Failed'
        ],

        ErrorCode::DATA_NOT_FOUND => [
            'statusCode' => 404,
            'message'    => 'Data: Not Found'
        ],

        ErrorCode::POST_DATA_NOT_PROVIDED => [
            'statusCode' => 400,
            'message'    => 'Postdata: Not provided'
        ],

        ErrorCode::POST_DATA_INVALID => [
            'statusCode' => 400,
            'message'    => 'Postdata: Invalid'
        ]
    ];

    /**
     * @param $code
     *
     * @return array|null
     */
    public function get($code)
    {
        return $this->has($code) ? $this->errors[$code] : null;
    }

    /**
     * @param $code
     *
     * @return bool
     */
    public function has($code)
    {
        return array_key_exists($code, $this->errors);
    }

    /**
     * @param $code
     * @param $message
     * @param $statusCode
     *
     * @return static
     */
    public function error($code, $message, $statusCode)
    {
        $this->errors[$code] = [
            'statusCode' => $statusCode,
            'message'    => $message
        ];

        return $this;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }
}