<?php

namespace App\Exceptions;

use RuntimeException;

class ReferencePartiallyReversedException extends RuntimeException
{
    public function __construct(string $referenceId)
    {
        parent::__construct('Reference partially reversed: ' . $referenceId);
    }
}
