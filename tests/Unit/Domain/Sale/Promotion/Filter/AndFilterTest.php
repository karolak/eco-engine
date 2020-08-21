<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Filter;

use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Item;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Product;
use Karolak\EcoEngine\Domain\Sale\Promotion\Filter\AndFilter;
use Karolak\EcoEngine\Domain\Sale\Promotion\Filter\EmptyFilter;
use PHPUnit\Framework\TestCase;

/**
 * Class AndFilterTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Filter
 */
class AndFilterTest extends TestCase
{
    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnAllItems_When_AllFiltersReturnAllItems()
    {
        // Arrange
        $items = [
            new Item(new Product('1', 100)),
            new Item(new Product('2', 200))
        ];
        $filter1 = new EmptyFilter();
        $filter2 = new EmptyFilter();
        $filter = new AndFilter($filter1, $filter2);

        // Act
        $result = $filter->filter($items);

        // Assert
        $this->assertCount(2, $result);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnEmptyItems_When_MinimumOneFilterReturnEmptyItems()
    {
        // Arrange
        $items = [
            new Item(new Product('1', 100)),
            new Item(new Product('2', 200))
        ];
        $filter1 = $this->createMock(EmptyFilter::class);
        $filter1->expects($this->once())
            ->method('filter')
            ->willReturn([]);
        $filter2 = new EmptyFilter();
        $filter = new AndFilter($filter1, $filter2);

        // Act
        $result = $filter->filter($items);

        // Assert
        $this->assertCount(0, $result);
    }
}