<?php

namespace Phikhi\UrlToText\Exceptions;

use Exception;

final class TagsNotProvidedException extends Exception
{
    public function __construct(string $message = null, int $code = 0, Exception $previous = null)
    {
        $this->message = (! is_null($message)) ? $message : 'No tags has been set. Please provide a valid tags array.';

        parent::__construct($this->message, $code, $previous);
    }
}
