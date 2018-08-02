<?php

namespace src\Integration; // Сменить неймспейс - не ясно это директория или неймспейс

class DataProvider
{
    private $host;
    private $user;
    private $password;

    /**
     * @param $host
     * @param $user
     * @param $password
     */
    public function __construct($host, $user, $password) // строгая типизация для более понятного кода
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * @param array $request
     *
     * @return array
     */
    public function get(array $request) // переименова в getFromExternalService так как без коментария не понятно что получаем
    {
        // returns a response from external service
    }
}