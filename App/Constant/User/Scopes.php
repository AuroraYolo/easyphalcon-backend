<?php
namespace Backend\Constant\User;

class Scopes
{

    /**
     * 公用,无需登录验证
     */
    const SCOPES_UNAUTHORIZED = "unauthorized";
    /**
     * 普通用户
     */
    const SCOPES_COMMON_USERS = "common_user";
    /**
     * 平台用户
     */
    const SCOPES_MANAGER_USERS = "manager_user";
    /**
     * 系统管理员
     */
    const SCOPES_SUPER_USERS = "super_user";

    /**
     * 企业账户：拥有所有权限
     */
    const SCOPES_ENT_ADMIN = 'ent_admin';

    /**
     * 企业子用户
     */
    const SCOPES_ENT_USER = 'ent_user';

    /**
     * Dashboard资源
     */
    const DASHBOARD = 'dashboard';
}