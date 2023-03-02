<?php

namespace Phikhi\UrlToText\Enums;

trait HasValues
{
    /**
     * @return mixed[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
