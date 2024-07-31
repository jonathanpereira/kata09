<?php

namespace App;

interface PricingStrategyInterface
{
    public function calculate(int $itemsQuantity): float;
}
