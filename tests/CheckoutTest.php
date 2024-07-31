<?php

use App\DefaultPriceStrategy;
use App\DiscountPriceStrategy;
use App\Checkout;

use PHPUnit\Framework\TestCase;

class CheckoutTest extends TestCase
{

    private function getPricingRules(): array
    {
        return [
            'A' => new DiscountPriceStrategy(
                unitPrice: 50,
                minimumQuantity: 3,
                specialPrice: 130
            ),
            'B' => new DiscountPriceStrategy(
                unitPrice: 30,
                minimumQuantity: 2,
                specialPrice: 45
            ),
            'C' => new DefaultPriceStrategy(
                unitPrice: 20
            ),
            'D' => new DefaultPriceStrategy(
                unitPrice: 15
            ),
        ];
    }
    private function price(string $goods): float
    {
        $checkout = new Checkout($this->getPricingRules());

        foreach (str_split($goods) as $good) {
            $checkout->scan($good);
        }

        return $checkout->total();
    }

    public function testTotals(): void
    {
        $this->assertEquals(0, $this->price(''));
        $this->assertEquals(50, $this->price('A'));
        $this->assertEquals(80, $this->price('AB'));
        $this->assertEquals(115, $this->price('CDBA'));

        $this->assertEquals(100, $this->price('AA'));
        $this->assertEquals(130, $this->price('AAA'));
        $this->assertEquals(180, $this->price('AAAA'));
        $this->assertEquals(230, $this->price('AAAAA'));
        $this->assertEquals(260, $this->price('AAAAAA'));

        $this->assertEquals(160, $this->price('AAAB'));
        $this->assertEquals(175, $this->price('AAABB'));
        $this->assertEquals(190, $this->price('AAABBD'));
        $this->assertEquals(190, $this->price('DABABA'));
    }

    public function testIncremental(): void
    {
        $checkout = new Checkout($this->getPricingRules());

        $this->assertEquals(0, $checkout->total());

        $checkout->scan('A');
        $this->assertEquals(50, $checkout->total());

        $checkout->scan('B');
        $this->assertEquals(80, $checkout->total());

        $checkout->scan('A');
        $this->assertEquals(130, $checkout->total());

        $checkout->scan('A');
        $this->assertEquals(160, $checkout->total());

        $checkout->scan('B');
        $this->assertEquals(175, $checkout->total());
    }

    public function testException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $checkout = new Checkout($this->getPricingRules());
        $checkout->scan('E');
        $checkout->total();
    }
}
