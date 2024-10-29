<?php

namespace Vlad\FishChat\Controllers;

use Vlad\FishChat\Auth\Authentication;
use Vlad\FishChat\core\Request;
use Vlad\FishChat\core\Response;

class AuthController
{
    private Authentication $auth;

    public function __construct()
    {
        $this->auth = new Authentication();
    }

    public function login(Request $request, Response $response): \Psr\Http\Message\ResponseInterface
    {
        $data = $request->all();

        $login = $this->auth->login($data['email'], $data['password']);

        if($login && $login['error']) {
            return $response->create(400, json_encode(['error' => $login['message']]), ['Content-Type' => 'application/json']);
        }

        return $response->create(200, json_encode(['message' => 'login successful']), ['Content-Type' => 'application/json']);
    }

    public function register(Request $request, Response $response): \Psr\Http\Message\ResponseInterface
    {
        $data = $request->all();

        $params = [
            'name' => $data['name'],
            'surname' => $data['surname'],
        ];

        $result = $this->auth->register($data['email'], $data['password'], $data['confirm_password'], $params);

        if($result && $result['error']) {
            return $response->create(400, json_encode(['error' => $result['message']]), ['Content-Type' => 'application/json']);
        }

        return $response->create(201, json_encode(['message' => 'registration successful']), ['Content-Type' => 'application/json']);
    }

    public function logout(Request $request, Response $response): \Psr\Http\Message\ResponseInterface
    {
        $sessionHash = $request->getCookieParams()['phpauth_session_cookie'] ?? null;

        if($sessionHash) {
           $logout = $this->auth->logout($sessionHash);

            if ($logout) {
                return $response->create(200, json_encode(['message' => 'successful to log out']), ['Content-Type' => 'application/json']);
            } else {
                return $response->create(400, json_encode(['error' => 'Failed to log out']), ['Content-Type' => 'application/json']);
            }
        }

        return $response->create(401, json_encode(['error' => 'Session not found']), ['Content-Type' => 'application/json']);
    }
}