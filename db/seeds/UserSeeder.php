<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

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
        $data = [
            ['name' => 'Vladislav', 'email' => 'vlad@example.com', 'password' => 'password123'],
            ['name' => 'Vasya', 'email' => 'vasya@example.com', 'password' => 'password321'],
            ['name' => 'Kate', 'email' => 'kate@example.com', 'password' => 'password888'],
        ];

        $this->table('users')->insert($data)->save();
    }
}
