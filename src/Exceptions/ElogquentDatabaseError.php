<?php

namespace Elogquent\Exceptions;

use Exception;

class ElogquentDatabaseError extends Exception
{
    public function __construct(string $error)
    {
        parent::__construct('Error storing in database: ['.$error.']');
    }
}
