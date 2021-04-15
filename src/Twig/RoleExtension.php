<?php
namespace App\Twig;

use App\Entity\User;
use App\Utils\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class RoleExtension extends AbstractExtension
{
    private RoleHierarchyInterface $roleHierarchy;

    public function __construct(RoleHierarchyInterface $roleHierarchy)
    {
        $this->roleHierarchy = $roleHierarchy;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('getRoles', [$this, 'getRoles']),
            new TwigFunction('hasRole', [$this, 'hasRole'])
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('highestRole', [$this, 'highestRole']),
            new TwigFilter('nameRole', [$this, 'nameRole'])
        ];
    }

    public function hasRole(User $user, string $role): bool
    {
        return $this->highestRole($user) === $role;
    }

    public function highestRole(User $user): string
    {
        return $this->roleHierarchy->getReachableRoleNames($user->getRoles())[0] ?? 'ROLE_ERROR';
    }

    public function nameRole(string $role): string
    {
        return Role::getName($role);
    }

    public function getRoles(): array
    {
        return Role::gets();
    }
}
