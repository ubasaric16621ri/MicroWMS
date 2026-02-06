<?php

namespace App\Exceptions;

use RuntimeException;

class ReferenceNotFoundException extends RuntimeException
{
    public function __construct(string $referenceId)
    {
        parent::__construct('Reference not found: ' . $referenceId);
    }
}
