<?php
namespace Backend\Services\User;

class PhoneAccount implements AccountType
{

    const ACCOUNT_NAME = 'phone';
    const CODE = 'code';

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

    /**
     * @return object
     * @throws \ReflectionException
     */
    public static function getInstance()
    {
        // TODO: Implement getInstance() method.
        $reflector = new \ReflectionClass(PhoneAccount::class);
        return $reflector->newInstance();
    }
}