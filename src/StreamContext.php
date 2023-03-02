<?php

namespace Phikhi\UrlToText;

final class StreamContext
{
    /**
     * @return mixed[]
     */
    public static function options(): array
    {
        return [
            'http' => [
                'header' => 'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36',
            ],
        ];
    }
}
