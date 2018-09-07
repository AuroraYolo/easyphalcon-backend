<?php
namespace Backend\Services\User;

interface AccountType
{
    /**
     * @param array $data login data
     *
     * @return string $identity
     */
    public function login($data);

    /**
     * @param array $data
     *
     * @return string $identity
     */
    public function register($data);

    /**
     * @param string $identity Identity
     *
     * @return  bool Authentication successful
     */
    public function authenticate($identity);
}