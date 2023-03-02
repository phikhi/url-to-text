<?php

namespace Phikhi\UrlToText\Enums;

enum AllowedTags: string
{
    use HasValues;

    case H1 = 'h1';
    case H2 = 'h2';
    case H3 = 'h3';
    case H4 = 'h4';
    case H5 = 'h5';
    case H6 = 'h6';
    case P = 'p';
    case LI = 'li';
    case A = 'a';
}
