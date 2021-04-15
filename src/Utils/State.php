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
}
