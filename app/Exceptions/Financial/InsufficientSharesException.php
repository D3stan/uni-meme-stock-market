<?php

namespace App\Exceptions\Financial;

use Exception;

class InsufficientSharesException extends Exception
{
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
