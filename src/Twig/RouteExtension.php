<?php
namespace App\Twig;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RouteExtension extends AbstractExtension
{
    private Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('setActive', [$this, 'setActive'])
        ];
    }

    public function setActive(string $route, string $clazz = 'active'): string
    {
        return $this->router->getContext()->getParameter('_route') === $route ? $clazz : '';
    }
}
