<?php
namespace Backend\Services\User;

class UsernameAccount implements AccountType
{

    const ACCOUNT_NAME = 'username';
    const PASSWORD = 'password';

    public function authenticate($identity)
    {
        // TODO: Implement authenticate() method.
    }

    public function register($data)
    {
        // TODO: Implement register() method.
    }

    public function login($data)
    {
        // TODO: Implement login() method.
    }
}