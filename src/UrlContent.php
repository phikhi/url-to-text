<?php

namespace Phikhi\UrlToText;

class UrlContent
{
    public static function fetch(string $url): string|false
    {
        return file_get_contents(
            $url,
            false,
            stream_context_create(StreamContext::options())
        );
    }
}
