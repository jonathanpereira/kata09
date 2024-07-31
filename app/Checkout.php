<?php

namespace App;

use InvalidArgumentException;

class Checkout
{
    public function __construct(
        protected readonly array $pricingRules,
        protected array $items = []
    )
    {
    }

    public function scan($item): void
    {
        $this->items[$item] = ($this->items[$item] ?? 0) + 1;
    }

    public function total(): float
    {
        $total = 0;

        foreach ($this->items as $itemName => $itemCount) {
            $total += $this->calculateItemTotal($itemName, $itemCount);
        }

        return $total;
    }

    private function calculateItemTotal(string $itemName, int $itemCount): float
    {
        if (!isset($this->pricingRules[$itemName])) {
            throw new InvalidArgumentException(sprintf("No pricing rule found for item: %s", $itemName));
        }

        return $this->pricingRules[$itemName]->calculate($itemCount);
    }
}
