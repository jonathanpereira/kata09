<?php

namespace App;

readonly class DefaultPriceStrategy implements PricingStrategyInterface
{
    public function __construct(
        protected float $unitPrice
    )
    {
    }

    public function calculate(int $itemsQuantity): float
    {
        return $itemsQuantity * $this->unitPrice;
    }
}
