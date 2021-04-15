<?php


namespace App\Twig;


use App\Utils\State;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class StateExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('stateName',[$this,'stateName'])
        ];
    }

    public function stateName(int $state)
    {
        return State::getValues()[$state] ?? 'undefined';
    }
}
