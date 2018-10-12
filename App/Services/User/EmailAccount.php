<?php
namespace Backend\Services\User;

class EmailAccount implements AccountType
{

    const ACCOUNT_NAME = 'email';
    const PASSWORD = 'password';

    public function login($data)
    {
        // TODO: Implement login() method.
    }

    public function register($data)
    {
        // TODO: Implement register() method.
    }

    public function authenticate($identity)
    {
        // TODO: Implement authenticate() method.
    }

    /**
     * @return object
     * @throws \ReflectionException
     */
    public static function getInstance()
    {
        // TODO: Implement getInstance() method.
        $reflector = new \ReflectionClass(EmailAccount::class);
        return $reflector->newInstance();
    }
}