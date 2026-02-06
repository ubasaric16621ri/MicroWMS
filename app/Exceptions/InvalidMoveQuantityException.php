<?php

namespace App\Exceptions;

use RuntimeException;

class InvalidMoveQuantityException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Invalid move quantity.');
    }
}