<?php

namespace App\Exceptions;

class InvalidImportException extends \Exception
{
    public function __construct(int $productId, int $locationId, int $quantity)
    {
        parent::__construct(
            'Invalid import data for product_id=' . $productId .
                ', location_id=' . $locationId .
                ': quantity must be > 0 (got ' . $quantity . ').'
        );
    }
}
