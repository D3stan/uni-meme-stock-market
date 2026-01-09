<?php

namespace App\Exceptions\Market;

use Exception;

/**
 * Exception thrown when an operation is attempted on a market or asset that is currently suspended.
 */
class MarketSuspendedException extends Exception
{
    /**
     * Initialize the exception.
     *
     * @param string|null $ticker The ticker symbol of the suspended asset (optional).
     * @param string|null $message Optional custom exception message.
     */
    public function __construct(?string $ticker = null, ?string $message = null)
    {
        $defaultMessage = $ticker
            ? "Trading is currently suspended for $ticker."
            : 'Trading is currently suspended for this asset.';

        parent::__construct($message ?? $defaultMessage);
    }
}