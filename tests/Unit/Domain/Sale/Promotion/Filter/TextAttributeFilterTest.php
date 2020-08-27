<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Filter;

use Karolak\EcoEngine\Domain\Common\ValueObject\ListAttribute;
use Karolak\EcoEngine\Domain\Common\ValueObject\NumericAttribute;
use Karolak\EcoEngine\Domain\Common\ValueObject\TextAttribute;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Item;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Product;
use Karolak\EcoEngine\Domain\Sale\Promotion\Filter\TextAttributeFilter;
use PHPUnit\Framework\TestCase;

/**
 * Class TextAttributeFilterTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Filter
 */
class TextAttributeFilterTest extends TestCase
{
    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ActRight_When_StrictCompare()
    {
        // Arrange
        $items = $this->getOrderItemsWithDifferentProductAttributes();
        $filter1 = new TextAttributeFilter(new TextAttribute('size', 'XXL'), TextAttributeFilter::STRICT);
        $filter2 = new TextAttributeFilter(new TextAttribute('size', 'XxL'), TextAttributeFilter::STRICT);
        $filter3 = new TextAttributeFilter(new TextAttribute('size', 'XL'), TextAttributeFilter::STRICT);
        $filter4 = new TextAttributeFilter(new TextAttribute('size', 'xl'), TextAttributeFilter::STRICT);
        $filter5 = new TextAttributeFilter(new TextAttribute('size', ''), TextAttributeFilter::STRICT);

        // Act
        $result1 = $filter1->filter($items);
        $result2 = $filter2->filter($items);
        $result3 = $filter3->filter($items);
        $result4 = $filter4->filter($items);
        $result5 = $filter5->filter($items);

        // Assert
        $this->assertCount(1, $result1);
        $this->assertCount(0, $result2);
        $this->assertCount(0, $result3);
        $this->assertCount(0, $result4);
        $this->assertCount(0, $result5);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ActRight_When_CaseInsensitiveCompare()
    {
        // Arrange
        $items = $this->getOrderItemsWithDifferentProductAttributes();
        $filter1 = new TextAttributeFilter(new TextAttribute('size', 'XXL'), TextAttributeFilter::CASE_INSENSITIVE);
        $filter2 = new TextAttributeFilter(new TextAttribute('size', 'XxL'), TextAttributeFilter::CASE_INSENSITIVE);
        $filter3 = new TextAttributeFilter(new TextAttribute('size', 'XL'), TextAttributeFilter::CASE_INSENSITIVE);
        $filter4 = new TextAttributeFilter(new TextAttribute('size', 'xl'), TextAttributeFilter::CASE_INSENSITIVE);
        $filter5 = new TextAttributeFilter(new TextAttribute('size', ''), TextAttributeFilter::CASE_INSENSITIVE);

        // Act
        $result1 = $filter1->filter($items);
        $result2 = $filter2->filter($items);
        $result3 = $filter3->filter($items);
        $result4 = $filter4->filter($items);
        $result5 = $filter5->filter($items);

        // Assert
        $this->assertCount(1, $result1);
        $this->assertCount(1, $result2);
        $this->assertCount(0, $result3);
        $this->assertCount(0, $result4);
        $this->assertCount(0, $result5);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ActRight_When_ContainsCompare()
    {
        // Arrange
        $items = $this->getOrderItemsWithDifferentProductAttributes();
        $filter1 = new TextAttributeFilter(new TextAttribute('size', 'XXL'), TextAttributeFilter::CONTAINS);
        $filter2 = new TextAttributeFilter(new TextAttribute('size', 'XxL'), TextAttributeFilter::CONTAINS);
        $filter3 = new TextAttributeFilter(new TextAttribute('size', 'XL'), TextAttributeFilter::CONTAINS);
        $filter4 = new TextAttributeFilter(new TextAttribute('size', 'xl'), TextAttributeFilter::CONTAINS);
        $filter5 = new TextAttributeFilter(new TextAttribute('size', ''), TextAttributeFilter::CONTAINS);

        // Act
        $result1 = $filter1->filter($items);
        $result2 = $filter2->filter($items);
        $result3 = $filter3->filter($items);
        $result4 = $filter4->filter($items);
        $result5 = $filter5->filter($items);

        // Assert
        $this->assertCount(1, $result1);
        $this->assertCount(0, $result2);
        $this->assertCount(1, $result3);
        $this->assertCount(0, $result4);
        $this->assertCount(1, $result5);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ActRight_When_ContainsCaseInsensitiveCompare()
    {
        // Arrange
        $items = $this->getOrderItemsWithDifferentProductAttributes();
        $filter1 = new TextAttributeFilter(new TextAttribute('size', 'XXL'), TextAttributeFilter::CONTAINS_CASE_INSENSITIVE);
        $filter2 = new TextAttributeFilter(new TextAttribute('size', 'XxL'), TextAttributeFilter::CONTAINS_CASE_INSENSITIVE);
        $filter3 = new TextAttributeFilter(new TextAttribute('size', 'XL'), TextAttributeFilter::CONTAINS_CASE_INSENSITIVE);
        $filter4 = new TextAttributeFilter(new TextAttribute('size', 'xl'), TextAttributeFilter::CONTAINS_CASE_INSENSITIVE);
        $filter5 = new TextAttributeFilter(new TextAttribute('size', ''), TextAttributeFilter::CONTAINS_CASE_INSENSITIVE);
        $filter6 = new TextAttributeFilter(new TextAttribute('size', 'A'), TextAttributeFilter::CONTAINS_CASE_INSENSITIVE);

        // Act
        $result1 = $filter1->filter($items);
        $result2 = $filter2->filter($items);
        $result3 = $filter3->filter($items);
        $result4 = $filter4->filter($items);
        $result5 = $filter5->filter($items);
        $result6 = $filter6->filter($items);

        // Assert
        $this->assertCount(1, $result1);
        $this->assertCount(1, $result2);
        $this->assertCount(1, $result3);
        $this->assertCount(1, $result4);
        $this->assertCount(1, $result5);
        $this->assertCount(0, $result6);
    }

    /**
     * @test
     */
    public function Should_ReturnNoItems_When_EmptyItems()
    {
        // Arrange
        $filter = new TextAttributeFilter(new TextAttribute('size', 'XXL'), TextAttributeFilter::CONTAINS_CASE_INSENSITIVE);

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