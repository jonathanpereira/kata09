<?php

namespace App;

readonly class DiscountPriceStrategy implements PricingStrategyInterface
{
    public function __construct(
        protected float $unitPrice,
        protected int   $minimumQuantity,
        protected float $specialPrice
    )
    {
    }

    public function calculate(int $itemsQuantity): float
    {
        $total = 0;
        $discountedSets = intdiv($itemsQuantity, $this->minimumQuantity);
        $remainingItems = $itemsQuantity % $this->minimumQuantity;

        $total += $discountedSets * $this->specialPrice;
        $total += $remainingItems * $this->unitPrice;

        return $total;
    }
}
