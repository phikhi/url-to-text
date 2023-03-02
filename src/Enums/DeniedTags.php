<?php

namespace Phikhi\UrlToText\Enums;

enum DeniedTags: string
{
    use HasValues;

    case SCRIPT = 'script';
    case STYLE = 'style';
}
