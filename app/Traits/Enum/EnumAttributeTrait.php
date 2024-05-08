<?php

namespace App\Traits\Enum;

trait EnumAttributeTrait {

    /**
     * @return array<int|string>
     */
    public static function values(): array
    {
        return array_map(function ($case) {
            return $case->value;
        }, self::cases());
    }
}
