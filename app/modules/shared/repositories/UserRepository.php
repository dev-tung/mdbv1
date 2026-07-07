<?php

class UserRepository extends Repository
{
    protected string $table = 'users';

    /* =================================================
       FIND
    ================================================= */

    public function findById(int $id): ?array
    {
        return Database::first(
            "SELECT *
             FROM users
             WHERE id = :id
             LIMIT 1",
            [
                'id' => $id
            ]
        );
    }

    public function findByUsername(string $username): ?array
    {
        return Database::first(
            "SELECT *
             FROM users
             WHERE username = :username
             LIMIT 1",
            [
                'username' => $username
            ]
        );
    }

    public function findByEmail(string $email): ?array
    {
        return Database::first(
            "SELECT *
             FROM users
             WHERE email = :email
             LIMIT 1",
            [
                'email' => $email
            ]
        );
    }
}