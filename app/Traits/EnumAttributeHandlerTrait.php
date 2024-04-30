<?php

namespace App\Traits;

trait EnumAttributeHandlerTrait {
    public static function values(): array
    {
        return array_map(function ($case) {
            return $case->value;
        }, self::cases());
    }
}
