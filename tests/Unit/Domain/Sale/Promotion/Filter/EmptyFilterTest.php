<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Filter;

use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Item;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Product;
use Karolak\EcoEngine\Domain\Sale\Promotion\Filter\EmptyFilter;
use PHPUnit\Framework\TestCase;

/**
 * Class EmptyFilterTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Filter
 */
class EmptyFilterTest extends TestCase
{
    /**
     * @test
     * @throws \Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException
     */
    public function Should_ReturnAllItems()
    {
        // Arrange
        $filter = new EmptyFilter();
        $items = [
            new Item(new Product('1', 100)),
            new Item(new Product('2', 200))
        ];

        // Act
        $result = $filter->filter($items);

        // Assert
        $this->assertEquals($items, $result);
    }
}