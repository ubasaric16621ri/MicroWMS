<?php

namespace App\Exceptions;

use RuntimeException;

class SameLocationMoveException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Cannot move from the same location. Source and destination must be different.');
    }
}
