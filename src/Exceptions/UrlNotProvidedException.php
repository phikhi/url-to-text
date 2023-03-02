<?php

namespace Phikhi\UrlToText\Exceptions;

use Exception;

final class UrlNotProvidedException extends Exception
{

    public function __construct(string $message = null, int $code = 0, Exception $previous = null)
    {
        $this->message = (! is_null($message)) ? $message : 'No url has been set. Please provide a valid url to extract text from.';

        parent::__construct($this->message, $code, $previous);
    }
}
