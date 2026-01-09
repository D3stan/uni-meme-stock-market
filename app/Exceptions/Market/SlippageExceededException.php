<?php

namespace App\Exceptions\Market;

use Exception;

/**
 * Exception thrown when the price of an asset changes significantly between preview and execution.
 */
class SlippageExceededException extends Exception
{
    protected float $actualTotal;

    /**
     * Initialize the exception based on unit price discrepancy.
     *
     * @param float $expectedPrice The price per share the user expected.
     * @param float $actualPrice The actual price per share at execution.
     * @param string|null $message Optional custom exception message.
     */
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

    /**
     * Create a new exception instance based on total cost discrepancy.
     *
     * @param float $expectedTotal The total transaction cost expected by the user.
     * @param float $actualTotal The actual total transaction cost.
     * @return self
     */
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

    /**
     * Get the actual total value that triggered the exception.
     *
     * @return float
     */
    public function getActualTotal(): float
    {
        return $this->actualTotal;
    }
}