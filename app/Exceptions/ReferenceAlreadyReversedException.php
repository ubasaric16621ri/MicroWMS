<?php

namespace App\Exceptions;

use RuntimeException;

class ReferenceAlreadyReversedException extends RuntimeException
{
    public function __construct(string $referenceId)
    {
        parent::__construct('Reference already reversed: ' . $referenceId);
    }
}
