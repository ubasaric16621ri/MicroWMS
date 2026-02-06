<?php

namespace App\Exceptions;

use RuntimeException;

class ExportEmptyException extends RuntimeException
{
    public function __construct(string $exportName)
    {
        parent::__construct('CSV export is empty: ' . $exportName);
    }
}
