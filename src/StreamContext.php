<?php

namespace Phikhi\UrlToText;

class StreamContext
{
    public static function create(?array $options = null): mixed
    {
        return stream_context_create($options ?? [
            "http" => [
                "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
            ]
        ]);
    }
}
