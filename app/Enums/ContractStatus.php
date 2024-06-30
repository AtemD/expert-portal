<?php

namespace App\Enums;

// use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum ContractStatus: int implements HasLabel, HasColor
{
    case ACTIVE = 1;
    case TERMINATED = 2;

    // Check if an enum instance (on the client model) is 'Active'
    public function isActive(): bool
    {
        return $this === static::ACTIVE;
    }

    // Check if an enum instance (on the user model) is 'TERMINATED' 
    public function isTerminated(): bool
    {
        return $this === static::TERMINATED;
    }

    // Provide additional information that can be consumed by the front-end 
    public function toArray(): array
    {
        return [
            [
                "id" => static::ACTIVE,
                "name" => 'Active',
                "description" => "Active contract status",
                "color" => "success",
            ],
            [
                "id" => static::TERMINATED,
                "name" => 'Terminated',
                "description" => "Terminated contract status",
                "color" => "warning",
            ],
        ];
    }

    public static function toList(): array
    {
        return [
            static::ACTIVE => 'Active',
            static::TERMINATED => 'Terminated'
        ];
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            static::ACTIVE => "Active",
            static::TERMINATED => "Terminated",
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            static::ACTIVE => "success",
            static::TERMINATED => "danger",
        };
    }

    // public function getIcon(): ?string
    // {
    //     return match ($this) {
    //         self::ACTIVE => 'heroicon-m-pencil',
    //         self::TERMINATED => 'heroicon-m-eye',
    //     };
    // }
}
