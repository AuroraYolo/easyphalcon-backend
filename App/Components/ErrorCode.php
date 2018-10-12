<?php
namespace Backend\Components;

class ErrorCode
{
    /**
     * 通用
     */
    const GENERAL_SYSTEM = 1010;
    const GENERAL_NOT_IMPLEMENTED = 1020;
    const GENERAL_NOT_FOUND = 1030;

    /**
     * 用户认证
     */
    const AUTH_INVALID_ACCOUNT_TYPE = 2010;
    const AUTH_LOGIN_FAILED = 2020;
    const AUTH_TOKEN_INVALID = 2030;
    const AUTH_SESSION_EXPIRED = 2040;
    const AUTH_SESSION_INVALID = 2050;

    /**
     * 访问控制
     */
    const ACCESS_DENIED = 3010;

    /**
     * 客户端错误
     */
    const DATA_FAILED = 4010;
    const DATA_NOT_FOUND = 4020;
    const ACCOUNT_EXISTS = 4030;

    /**
     * 服务器错误
     */
    const POST_DATA_NOT_PROVIDED = 5010;
    const POST_DATA_INVALID = 5020;
}