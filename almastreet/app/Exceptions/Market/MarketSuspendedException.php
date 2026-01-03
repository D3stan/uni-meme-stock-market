<?php

namespace App\Exceptions\Market;

use Exception;

class MarketSuspendedException extends Exception
{
    public function __construct(string $ticker = null, string $message = null)
    {
        $defaultMessage = $ticker
            ? "Trading is currently suspended for $ticker."
            : 'Trading is currently suspended for this asset.';

        parent::__construct($message ?? $defaultMessage);
    }
}
