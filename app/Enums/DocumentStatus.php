<?php

declare(strict_types=1);

namespace App\Enums;

enum DocumentStatus: string
{
    case Lost = 'perdu';
    case AwaitingPickup = 'en_attente_de_retrait';
    case Returned = 'restitue';

    public function label(): string
    {
        return match ($this) {
            self::Lost => 'Perdu',
            self::AwaitingPickup => 'En attente de retrait',
            self::Returned => 'Restitué',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Lost => 'bg-danger',
            self::AwaitingPickup => 'bg-warning text-dark',
            self::Returned => 'bg-success',
        };
    }

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
