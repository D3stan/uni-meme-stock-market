<?php

namespace App\Exceptions\Financial;

use Exception;

/**
 * Exception thrown when a user attempts to sell more shares than they currently hold.
 */
class InsufficientSharesException extends Exception
{
    /**
     * Initialize the exception with share quantity details.
     *
     * @param int $requested The number of shares the user attempted to sell.
     * @param int $available The number of shares actually available in the portfolio.
     * @param string|null $ticker The ticker symbol of the asset (optional).
     * @param string|null $message Optional custom exception message.
     */
    public function __construct(int $requested, int $available, ?string $ticker = null, ?string $message = null)
    {
        $tickerInfo = $ticker ? " for $ticker" : '';
        $defaultMessage = sprintf(
            'Insufficient shares%s: attempting to sell %d shares, but only %d available.',
            $tickerInfo,
            $requested,
            $available
        );

        parent::__construct($message ?? $defaultMessage);
    }
}