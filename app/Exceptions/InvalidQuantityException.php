<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class InvalidQuantityException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param  string  $message
     * @param  \Throwable|null  $previous
     * @return void
     */
    public function __construct($message = "Invalid quantity", Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
