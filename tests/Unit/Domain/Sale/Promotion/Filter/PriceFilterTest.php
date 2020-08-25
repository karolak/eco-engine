<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Filter;

use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Item;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Product;
use Karolak\EcoEngine\Domain\Sale\Promotion\Filter\PriceFilter;
use PHPUnit\Framework\TestCase;

/**
 * Class PriceFilterTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Filter
 */
class PriceFilterTest extends TestCase
{
    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnItems()
    {
        // Arrange
        $items = [
            new Item(new Product('1', 1000))
        ];
        $filterEquals = new PriceFilter(1000, PriceFilter::EQUALS);
        $filterEqualsOrLower1 = new PriceFilter(1000, PriceFilter::EQUALS_OR_LOWER);
        $filterEqualsOrLower2 = new PriceFilter(1001, PriceFilter::EQUALS_OR_LOWER);
        $filterEqualsOrHigher1 = new PriceFilter(1000, PriceFilter::EQUALS_OR_HIGHER);
        $filterEqualsOrHigher2 = new PriceFilter(999, PriceFilter::EQUALS_OR_HIGHER);
        $filterLower = new PriceFilter(1001, PriceFilter::LOWER);
        $filterHigher = new PriceFilter(999, PriceFilter::HIGHER);

        // Act
        $result1 = $filterEquals->filter($items);
        $result2 = $filterEqualsOrLower1->filter($items);
        $result3 = $filterEqualsOrLower2->filter($items);
        $result4 = $filterEqualsOrHigher1->filter($items);
        $result5 = $filterEqualsOrHigher2->filter($items);
        $result6 = $filterLower->filter($items);
        $result7 = $filterHigher->filter($items);

        // Assert
        $this->assertNotEmpty($result1);
        $this->assertNotEmpty($result2);
        $this->assertNotEmpty($result3);
        $this->assertNotEmpty($result4);
        $this->assertNotEmpty($result5);
        $this->assertNotEmpty($result6);
        $this->assertNotEmpty($result7);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnNoItems()
    {
        // Arrange
        $items = [
            new Item(new Product('1', 1000))
        ];
        $filterEquals = new PriceFilter(800, PriceFilter::EQUALS);
        $filterEqualsOrLower = new PriceFilter(500, PriceFilter::EQUALS_OR_LOWER);
        $filterEqualsOrHigher = new PriceFilter(1800, PriceFilter::EQUALS_OR_HIGHER);
        $filterLower = new PriceFilter(800, PriceFilter::LOWER);
        $filterHigher = new PriceFilter(1700, PriceFilter::HIGHER);

        // Act
        $result1 = $filterEquals->filter($items);
        $result2 = $filterEqualsOrLower->filter($items);
        $result3 = $filterEqualsOrHigher->filter($items);
        $result4 = $filterLower->filter($items);
        $result5 = $filterHigher->filter($items);
        $result6 = $filterHigher->filter([]);

        // Assert
        $this->assertEmpty($result1);
        $this->assertEmpty($result2);
        $this->assertEmpty($result3);
        $this->assertEmpty($result4);
        $this->assertEmpty($result5);
        $this->assertEmpty($result6);
    }
}