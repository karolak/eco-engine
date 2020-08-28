<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Filter;

use Karolak\EcoEngine\Domain\Common\Comparator\ListAttributesComparator;
use Karolak\EcoEngine\Domain\Common\ValueObject\ListAttribute;
use Karolak\EcoEngine\Domain\Common\ValueObject\NumericAttribute;
use Karolak\EcoEngine\Domain\Common\ValueObject\TextAttribute;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Item;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Product;
use Karolak\EcoEngine\Domain\Sale\Promotion\Filter\ListAttributeFilter;
use Karolak\EcoEngine\Domain\Sale\Promotion\Filter\NumericAttributeFilter;
use PHPUnit\Framework\TestCase;

/**
 * Class ListAttributeFilterTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Filter
 */
class ListAttributeFilterTest extends TestCase
{
    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ActRight_When_StrictCompare()
    {
        // Arrange
        $items = $this->getOrderItemsWithDifferentProductAttributes();
        $filter1 = new ListAttributeFilter(new ListAttribute('categories', [1, 2, 3, 4, 5]), ListAttributesComparator::STRICT);
        $filter2 = new ListAttributeFilter(new ListAttribute('categories', [1, 2, 5, 4, 3]), ListAttributesComparator::STRICT);
        $filter3 = new ListAttributeFilter(new ListAttribute('categories', [1, 2, 4, 5]), ListAttributesComparator::STRICT);
        $filter4 = new ListAttributeFilter(new ListAttribute('categories', [1, 2, 3, 4, 5, 6]), ListAttributesComparator::STRICT);

        // Act
        $result1 = $filter1->filter($items);
        $result2 = $filter2->filter($items);
        $result3 = $filter3->filter($items);
        $result4 = $filter4->filter($items);

        // Assert
        $this->assertCount(1, $result1);
        $this->assertCount(1, $result2);
        $this->assertCount(0, $result3);
        $this->assertCount(0, $result4);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ActRight_When_InCompare()
    {
        // Arrange
        $items = $this->getOrderItemsWithDifferentProductAttributes();
        $filter1 = new ListAttributeFilter(new ListAttribute('categories', [1, 2, 3, 4, 5]), ListAttributesComparator::IN);
        $filter2 = new ListAttributeFilter(new ListAttribute('categories', [1, 2, 5, 4, 3]), ListAttributesComparator::IN);
        $filter3 = new ListAttributeFilter(new ListAttribute('categories', [1, 2, 4, 5]), ListAttributesComparator::IN);
        $filter4 = new ListAttributeFilter(new ListAttribute('categories', [1, 2, 3, 4, 5, 6]), ListAttributesComparator::IN);
        $filter5 = new ListAttributeFilter(new ListAttribute('categories', [6, 7, 8]), ListAttributesComparator::IN);

        // Act
        $result1 = $filter1->filter($items);
        $result2 = $filter2->filter($items);
        $result3 = $filter3->filter($items);
        $result4 = $filter4->filter($items);
        $result5 = $filter5->filter($items);

        // Assert
        $this->assertCount(1, $result1);
        $this->assertCount(1, $result2);
        $this->assertCount(1, $result3);
        $this->assertCount(1, $result4);
        $this->assertCount(0, $result5);
    }

    /**
     * @test
     */
    public function Should_ReturnNoItems_When_CompareDifferentTypeOfAttributeWithSameName()
    {
        // Arrange
        $items = $this->getOrderItemsWithDifferentProductAttributes();
        $filter = new NumericAttributeFilter(new NumericAttribute('categories', 12345));

        // Act
        $result = $filter->filter($items);

        // Assert
        $this->assertCount(0, $result);
    }

    /**
     * @test
     */
    public function Should_ReturnNoItems_When_EmptyItems()
    {
        // Arrange
        $filter = new ListAttributeFilter(new ListAttribute('categories', [1, 2, 3, 4, 5]), ListAttributesComparator::IN);

        // Act
        $result = $filter->filter([]);

        // Assert
        $this->assertCount(0, $result);
    }

    /**
     * @return array
     * @throws InvalidPriceValueException
     */
    private function getOrderItemsWithDifferentProductAttributes(): array
    {
        $sizeAttribute = new TextAttribute('size', 'XXL');
        $totalAttribute = new NumericAttribute('total', 12345);
        $categoriesAttribute = new ListAttribute('categories', [1, 2, 3, 4, 5]);

        return [
            new Item(new Product('1', 2000)),
            new Item(new Product('2', 1000, [
                $sizeAttribute,
                $totalAttribute,
                $categoriesAttribute
            ]))
        ];
    }
}