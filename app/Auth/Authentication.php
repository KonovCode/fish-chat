<?php

namespace Vlad\FishChat\Auth;

use PDO;
use PHPAuth\Auth;
use PHPAuth\Config;
use Vlad\FishChat\core\DbConnection;

class Authentication
{
    private PDO $db;
    private Auth $auth;

    public function __construct()
    {
        $this->db = DbConnection::getConnection();

        $config = new Config($this->db, null);

        $this->auth = new Auth($this->db, $config);
    }

    public function register(string $email, string $password, string $repeatPassword, array $params = []): array
    {
        return $this->auth->register($email, $password, $repeatPassword, $params);
    }

    public function login(string $email, string $password): array
    {
        return $this->auth->login($email, $password);
    }

    public function logout(string $sessionHash): bool
    {
        return $this->auth->logout($sessionHash);
    }

    public function isLogged(): bool
    {
        return $this->auth->isLogged();
    }
}