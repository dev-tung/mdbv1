<?php

class Auth
{
    protected const SESSION_KEY = 'auth_user';

    /* =================================================
       LOGIN
    ================================================= */

    public static function login(array $user): void
    {
        Session::set(self::SESSION_KEY, $user);
    }

    public static function logout(): void
    {
        Session::remove(self::SESSION_KEY);
    }

    /* =================================================
       USER
    ================================================= */

    public static function user(): ?array
    {
        return Session::get(self::SESSION_KEY);
    }

    public static function id(): ?int
    {
        return (int) (self::user()['id'] ?? 0);
    }

    public static function role(): ?string
    {
        return self::user()['role'] ?? null;
    }

    /* =================================================
       CHECK
    ================================================= */

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function guest(): bool
    {
        return !self::check();
    }

    public static function hasRole(string|array $roles): bool
    {
        if (!self::check()) {
            return false;
        }

        return in_array(self::role(), (array) $roles, true);
    }
}
