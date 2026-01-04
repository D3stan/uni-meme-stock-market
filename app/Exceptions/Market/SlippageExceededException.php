<?php

namespace App\Exceptions\Market;

use Exception;

class SlippageExceededException extends Exception
{
    protected float $actualTotal;

    public function __construct(
        float $expectedPrice,
        float $actualPrice,
        ?string $message = null
    ) {
        $this->actualTotal = $actualPrice;

        $defaultMessage = sprintf(
            'Price has changed: expected %.4f CFU, actual %.4f CFU. Please review and confirm.',
            $expectedPrice,
            $actualPrice
        );

        parent::__construct($message ?? $defaultMessage);
    }

    public static function withTotal(float $expectedTotal, float $actualTotal): self
    {
        $message = sprintf(
            'Total cost has changed: expected %.2f CFU, actual %.2f CFU. Please review and confirm.',
            $expectedTotal,
            $actualTotal
        );

        $exception = new self($expectedTotal, $actualTotal, $message);
        $exception->actualTotal = $actualTotal;
        return $exception;
    }

    public function getActualTotal(): float
    {
        return $this->actualTotal;
    }
}
