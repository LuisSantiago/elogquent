<?php

namespace Elogquent\Exceptions;

use Exception;

class ElogquentInstallingError extends Exception
{
    public function __construct(string $error)
    {
        parent::__construct('Error installing: ['.$error.']');
    }
}
