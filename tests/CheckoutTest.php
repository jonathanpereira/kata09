<?php

use App\DefaultPriceStrategy;
use App\DiscountPriceStrategy;
use App\Checkout;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
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

    #[Test]
    #[DataProvider('totalsDataProvider')]
    public function itShouldCalculateTotals(int $expected, string $goods): void
    {
        $this->assertEquals($expected, $this->price($goods));
    }

    public static function totalsDataProvider(): array
    {
        return [
            [0, ''],
            [50, 'A'],
            [80, 'AB'],
            [115, 'CDBA'],
            [100, 'AA'],
            [130, 'AAA'],
            [180, 'AAAA'],
            [230, 'AAAAA'],
            [260, 'AAAAAA'],
            [160, 'AAAB'],
            [175, 'AAABB'],
            [190, 'AAABBD'],
            [190, 'DABABA'],
        ];
    }

    #[Test]
    public function itShouldTestIncremental(): void
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

    #[Test]
    public function itShouldTestException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $checkout = new Checkout($this->getPricingRules());
        $checkout->scan('E');
        $checkout->total();
    }
}
