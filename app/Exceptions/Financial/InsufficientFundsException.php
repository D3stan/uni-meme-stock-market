<?php

namespace App\Exceptions\Financial;

use Exception;

class InsufficientFundsException extends Exception
{
    public function __construct(float $required, float $available, ?string $message = null)
    {
        $defaultMessage = sprintf(
            'Insufficient funds: required %.2f CFU, but only %.2f CFU available.',
            $required,
            $available
        );

        parent::__construct($message ?? $defaultMessage);
    }
}
