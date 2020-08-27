<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Filter;

use Karolak\EcoEngine\Domain\Common\ValueObject\ListAttribute;
use Karolak\EcoEngine\Domain\Common\ValueObject\NumericAttribute;
use Karolak\EcoEngine\Domain\Common\ValueObject\TextAttribute;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Item;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Product;
use Karolak\EcoEngine\Domain\Sale\Promotion\Filter\NumericAttributeFilter;
use PHPUnit\Framework\TestCase;

/**
 * Class NumericAttributeFilterTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Filter
 */
class NumericAttributeFilterTest extends TestCase
{
    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ActRight_When_EqualCompare()
    {
        // Arrange
        $items = $this->getOrderItemsWithDifferentProductAttributes();
        $filter1 = new NumericAttributeFilter(new NumericAttribute('total', 12345), NumericAttributeFilter::EQUALS);
        $filter2 = new NumericAttributeFilter(new NumericAttribute('total', 1234), NumericAttributeFilter::EQUALS);

        // Act
        $result1 = $filter1->filter($items);
        $result2 = $filter2->filter($items);

        // Assert
        $this->assertCount(1, $result1);
        $this->assertCount(0, $result2);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ActRight_When_EqualsOrHigherCompare()
    {
        // Arrange
        $items = $this->getOrderItemsWithDifferentProductAttributes();
        $filter1 = new NumericAttributeFilter(new NumericAttribute('total', 12345), NumericAttributeFilter::EQUALS_OR_HIGHER);
        $filter2 = new NumericAttributeFilter(new NumericAttribute('total', 12), NumericAttributeFilter::EQUALS_OR_HIGHER);
        $filter3 = new NumericAttributeFilter(new NumericAttribute('total', 12346), NumericAttributeFilter::EQUALS_OR_HIGHER);

        // Act
        $result1 = $filter1->filter($items);
        $result2 = $filter2->filter($items);
        $result3 = $filter3->filter($items);

        // Assert
        $this->assertCount(1, $result1);
        $this->assertCount(1, $result2);
        $this->assertCount(0, $result3);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ActRight_When_EqualsOrLowerCompare()
    {
        // Arrange
        $items = $this->getOrderItemsWithDifferentProductAttributes();
        $filter1 = new NumericAttributeFilter(new NumericAttribute('total', 12345), NumericAttributeFilter::EQUALS_OR_LOWER);
        $filter2 = new NumericAttributeFilter(new NumericAttribute('total', 12346), NumericAttributeFilter::EQUALS_OR_LOWER);
        $filter3 = new NumericAttributeFilter(new NumericAttribute('total', 12344), NumericAttributeFilter::EQUALS_OR_LOWER);

        // Act
        $result1 = $filter1->filter($items);
        $result2 = $filter2->filter($items);
        $result3 = $filter3->filter($items);

        // Assert
        $this->assertCount(1, $result1);
        $this->assertCount(1, $result2);
        $this->assertCount(0, $result3);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ActRight_When_HigherCompare()
    {
        // Arrange
        $items = $this->getOrderItemsWithDifferentProductAttributes();
        $filter1 = new NumericAttributeFilter(new NumericAttribute('total', 12345), NumericAttributeFilter::HIGHER);
        $filter2 = new NumericAttributeFilter(new NumericAttribute('total', 12344), NumericAttributeFilter::HIGHER);
        $filter3 = new NumericAttributeFilter(new NumericAttribute('total', 12346), NumericAttributeFilter::HIGHER);

        // Act
        $result1 = $filter1->filter($items);
        $result2 = $filter2->filter($items);
        $result3 = $filter3->filter($items);

        // Assert
        $this->assertCount(0, $result1);
        $this->assertCount(1, $result2);
        $this->assertCount(0, $result3);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ActRight_When_LowerCompare()
    {
        // Arrange
        $items = $this->getOrderItemsWithDifferentProductAttributes();
        $filter1 = new NumericAttributeFilter(new NumericAttribute('total', 12345), NumericAttributeFilter::LOWER);
        $filter2 = new NumericAttributeFilter(new NumericAttribute('total', 12346), NumericAttributeFilter::LOWER);
        $filter3 = new NumericAttributeFilter(new NumericAttribute('total', 12344), NumericAttributeFilter::LOWER);

        // Act
        $result1 = $filter1->filter($items);
        $result2 = $filter2->filter($items);
        $result3 = $filter3->filter($items);

        // Assert
        $this->assertCount(0, $result1);
        $this->assertCount(1, $result2);
        $this->assertCount(0, $result3);
    }

    /**
     * @test
     */
    public function Should_ReturnNoItems_When_EmptyItems()
    {
        // Arrange
        $filter = new NumericAttributeFilter(new NumericAttribute('total', 12345), NumericAttributeFilter::LOWER);

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