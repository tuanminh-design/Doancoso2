<?php
namespace App\Helpers;

class Auth
{
    public static function check(): bool
    {
        return Session::has('user_id');
    }

    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }
        return Session::get('user');
    }

    public static function id(): ?int
    {
        return Session::get('user_id');
    }

    public static function name(): string
    {
        $u = self::user();
        return $u['name'] ?? 'Khách';
    }

    public static function email(): ?string
    {
        $u = self::user();
        return $u['email'] ?? null;
    }

    public static function isAdmin(): bool
    {
        if (!self::check()) {
            return false;
        }
        $u = self::user();
        return isset($u['role']) && $u['role'] === 'admin';
    }

    public static function login(array $user): void
    {
        Session::regenerate();
        unset($user['password']); // Never keep password in session
        Session::set('user_id', (int)$user['id']);
        Session::set('user', $user);
        Session::set('role', $user['role']);
    }

    public static function logout(): void
    {
        Session::remove('user_id');
        Session::remove('user');
        Session::remove('role');
        Session::regenerate();
    }

    public static function updateSessionUser(array $user): void
    {
        unset($user['password']);
        Session::set('user', $user);
    }
}
