<?php

namespace App\Exceptions;

use RuntimeException;

class InsufficientStockException extends RuntimeException
{
    public function __construct(int $available, int $requested)
    {
        parent::__construct('Insufficient stock. Available: ' . $available . ', Requested: ' . $requested);
    }
}
