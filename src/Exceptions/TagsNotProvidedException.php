<?php

namespace Phikhi\UrlToText\Exceptions;

use Exception;

class TagsNotProvidedException extends Exception
{
    protected $message = 'No tags has been set. Please provide a valid tags array.';

    public function __construct($message = null, $code = 0, Exception $previous = null) {
        if (!is_null($message)) {
            $this->message = $message;
        }
        parent::__construct($this->message, $code, $previous);
    }
}
