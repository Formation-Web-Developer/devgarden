<?php


namespace App\Utils;


class State
{
    public const VALIDATED = 1;
    public const WAITING = 0;
    public const REFUSED = -1;

    public static function getValues(): array
    {
        return [
            State::VALIDATED => 'Validé',
            State::WAITING => 'En attente',
            State::REFUSED => 'Refusé'
        ];
    }

    public static function getValue(int $state): string
    {
        return State::getValues()[$state] ?? 'Undefined';
    }

    public static function getIcons(): array
    {
        return [
            State::VALIDATED => '<i class="check icon"></i>',
            State::WAITING   => '<i class="hourglass icon"></i>',
            State::REFUSED   => '<i class="times icon"></i>'
        ];
    }

    public static function getIcon(int $state): string
    {
        return State::getIcons()[$state] ?? '';
    }

    public static function getValuesWithIcon(): array
    {
        $icons = State::getIcons();
        $values = State::getValues();
        return [
            State::VALIDATED => $icons[State::VALIDATED].' '.$values[State::VALIDATED],
            State::WAITING   => $icons[State::WAITING].' '.$values[State::WAITING],
            State::REFUSED   => $icons[State::REFUSED].' '.$values[State::REFUSED]
        ];
    }

    public static function getValueWithIcon(int $state): string
    {
        return State::getIcon($state) . ' ' . State::getValue($state);
    }
}
