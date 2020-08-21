<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Filter;

use Karolak\EcoEngine\Domain\Common\ValueObject\ListAttribute;
use Karolak\EcoEngine\Domain\Common\ValueObject\NumericAttribute;
use Karolak\EcoEngine\Domain\Common\ValueObject\TextAttribute;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Item;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Product;
use Karolak\EcoEngine\Domain\Sale\Promotion\Filter\ProductAttributeFilter;
use PHPUnit\Framework\TestCase;

/**
 * Class ProductAttributeFilterTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Filter
 */
class ProductAttributeFilterTest extends TestCase
{
    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnItems_When_StrictCompare()
    {
        // Arrange
        $items = $this->getOrderItemsWithDifferentProductAttributes();
        $filter1 = new ProductAttributeFilter(new TextAttribute('size', 'XXL'), true);
        $filter2 = new ProductAttributeFilter(new NumericAttribute('total', 12345), true);
        $filter3 = new ProductAttributeFilter(new ListAttribute('categories', [1, 2, 3, 4, 5]), true);

        // Act
        $result1 = $filter1->filter($items);
        $result2 = $filter2->filter($items);
        $result3 = $filter3->filter($items);

        // Assert
        $this->assertCount(1, $result1);
        $this->assertCount(1, $result2);
        $this->assertCount(1, $result3);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnItems_When_NotStrictCompareTextAttribute()
    {
        // Arrange
        $items = $this->getOrderItemsWithDifferentProductAttributes();
        $filter = new ProductAttributeFilter(new TextAttribute('size', 'L'), false);

        // Act
        $result = $filter->filter($items);

        // Assert
        $this->assertCount(1, $result);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnItems_When_NotStrictCompareNumericAttribute()
    {
        // Arrange
        $items = $this->getOrderItemsWithDifferentProductAttributes();
        $filter = new ProductAttributeFilter(new NumericAttribute('total', 12345), false);

        // Act
        $result = $filter->filter($items);

        // Assert
        $this->assertCount(1, $result);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnItems_When_NotStrictCompareListAttribute()
    {
        // Arrange
        $items = $this->getOrderItemsWithDifferentProductAttributes();
        $filter = new ProductAttributeFilter(new ListAttribute('categories', [2,3]), false);

        // Act
        $result = $filter->filter($items);

        // Assert
        $this->assertCount(1, $result);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnNoItems_When_CompareDifferentTypeOfAttribute()
    {
        // Arrange
        $items = $this->getOrderItemsWithDifferentProductAttributes();
        $filter = new ProductAttributeFilter(new NumericAttribute('size', 123), false);

        // Act
        $result = $filter->filter($items);

        // Assert
        $this->assertCount(0, $result);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnNoItems_When_AttributeNotFoundInNotEmptyItems()
    {
        // Arrange
        $items = $this->getOrderItemsWithDifferentProductAttributes();
        $filter = new ProductAttributeFilter(new TextAttribute('make', 'abc'));

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
        $items = [];
        $madeAttribute = new TextAttribute('make', 'abc');
        $filter = new ProductAttributeFilter($madeAttribute);

        // Act
        $result = $filter->filter($items);

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