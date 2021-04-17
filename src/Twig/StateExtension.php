<?php


namespace App\Twig;


use App\Utils\State;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class StateExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('validatedState', [$this, 'validatedState']),
            new TwigFunction('waitingState', [$this, 'waitingState']),
            new TwigFunction('refusedState', [$this, 'refusedState'])
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('stateName',[$this,'stateName']),
            new TwigFilter('iconRole', [$this, 'iconRole']),
            new TwigFilter('stateNameWithIcon', [$this, 'stateNameWithIcon']),
            new TwigFilter('isValidState', [$this, 'isValidState']),
            new TwigFilter('isWaitState', [$this, 'isWaitState']),
            new TwigFilter('isDeniedState', [$this, 'isDeniedState'])
        ];
    }

    public function stateName(int $state): string
    {
        return State::getValue($state);
    }

    public function stateNameWithIcon(int $state): string
    {
        return State::getValueWithIcon($state);
    }

    public function iconRole(int $state): string
    {
        return State::getIcon($state);
    }

    public function isValidState(int $state): bool
    {
        return $state === State::VALIDATED;
    }

    public function isWaitState(int $state): bool
    {
        return $state === State::WAITING;
    }

    public function isDeniedState(int $state): bool
    {
        return $state === State::REFUSED;
    }

    public function validatedState(): int
    {
        return State::VALIDATED;
    }

    public function waitingState(): int
    {
        return State::WAITING;
    }

    public function refusedState(): int
    {
        return State::REFUSED;
    }
}
