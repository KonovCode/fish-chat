<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;
use Vlad\FishChat\Auth\Authentication;

class UserSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        $auth = new Authentication();

        $data = [
            ['name' => 'testone', 'surname' => 'userOne', 'email' => 'testone@example.com', 'password' => 'test12345', 'confirm_password' => 'test12345'],
            ['name' => 'testtwo', 'surname' => 'userTwo', 'email' => 'testtwo@example.com', 'password' => 'test12345', 'confirm_password' => 'test12345'],
            ['name' => 'testthree', 'surname' => 'userThree', 'email' => 'testthree@example.com', 'password' => 'test12345', 'confirm_password' => 'test12345'],
        ];

        foreach ($data as $user) {
            $auth->register($user['email'], $user['password'], $user['confirm_password'], ['surname' => $user['surname'], 'name' => $user['name']]);
        }
    }
}
