<?php
namespace App\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RouteExtension extends AbstractExtension
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('setActive', [$this, 'setActive'])
        ];
    }

    public function setActive(string $route, string $clazz = 'active'): string
    {
        return $this->requestStack->getCurrentRequest()->get('_route') === $route ? $clazz : '';
    }
}
