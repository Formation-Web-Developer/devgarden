<?php

namespace App\Utils;


class Role
{
    public const SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    public const ADMIN = 'ROLE_ADMIN';
    public const MODERATOR = 'ROLE_MODERATOR';
    public const USER = 'ROLE_USER';

    public static function gets(): array
    {
        return [
            Role::SUPER_ADMIN => 'Super Administrateur',
            ROLE::ADMIN       => 'Administrateur',
            Role::MODERATOR   => 'Modérateur',
            Role::USER        => 'Utilisateur'
        ];
    }

    public static function getName(String $role): string
    {
        return Role::gets()[$role] ?? 'Anonyme';
    }

    public static function has(string $role): bool
    {
        return !empty(Role::gets()[$role]);
    }
}
