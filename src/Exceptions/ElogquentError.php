<?php

namespace Elogquent\Exceptions;

use Exception;

class ElogquentError extends Exception
{
    public function __construct(string $error)
    {
        parent::__construct('Error Elogquent logging: ['.$error.']');
    }
}
