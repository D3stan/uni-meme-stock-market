<?php

namespace App\Exceptions\Financial;

use Exception;

/**
 * Exception thrown when a user attempts a transaction without sufficient funds.
 */
class InsufficientFundsException extends Exception
{
    /**
     * Initialize the exception with detailed fund information.
     *
     * @param float $required The amount of CFU required for the operation.
     * @param float $available The amount of CFU currently available in the account.
     * @param string|null $message Optional custom exception message.
     */
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