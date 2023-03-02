<?php

namespace Phikhi\UrlToText\Exceptions;

use Exception;

class UrlNotProvidedException extends Exception
{
    protected $message = 'No url has been set. Please provide a valid url to extract text from.';

    public function __construct($message = null, $code = 0, Exception $previous = null) {
        if (!is_null($message)) {
            $this->message = $message;
        }
        parent::__construct($this->message, $code, $previous);
    }
}
