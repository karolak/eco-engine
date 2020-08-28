<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Condition;

use Karolak\EcoEngine\Domain\Common\Comparator\TextAttributesComparator;
use Karolak\EcoEngine\Domain\Common\ValueObject\ListAttribute;
use Karolak\EcoEngine\Domain\Common\ValueObject\NumericAttribute;
use Karolak\EcoEngine\Domain\Common\ValueObject\TextAttribute;
use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Product;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\ListAttributeCondition;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\NumericAttributeCondition;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\TextAttributeCondition;
use PHPUnit\Framework\TestCase;

/**
 * Class TextAttributeConditionTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Condition
 */
class TextAttributeConditionTest extends TestCase
{
    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnWrightResult_When_StrictCompare()
    {
        // Arrange
        $order = $this->getOrderWithDifferentProductAttributes();
        $condition1 = new TextAttributeCondition(new TextAttribute('size', 'XXL'), TextAttributesComparator::STRICT);
        $condition2 = new TextAttributeCondition(new TextAttribute('size', 'XxL'), TextAttributesComparator::STRICT);

        // Act
        $result1 = $condition1->isSatisfiedBy($order);
        $result2 = $condition2->isSatisfiedBy($order);

        // Assert
        $this->assertTrue($result1);
        $this->assertFalse($result2);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnWrightResult_When_CaseInsensitiveCompare()
    {
        // Arrange
        $order = $this->getOrderWithDifferentProductAttributes();
        $condition1 = new TextAttributeCondition(new TextAttribute('size', 'XXL'), TextAttributesComparator::CASE_INSENSITIVE);
        $condition2 = new TextAttributeCondition(new TextAttribute('size', 'XxL'), TextAttributesComparator::CASE_INSENSITIVE);
        $condition3 = new TextAttributeCondition(new TextAttribute('size', 'XxLl'), TextAttributesComparator::CASE_INSENSITIVE);

        // Act
        $result1 = $condition1->isSatisfiedBy($order);
        $result2 = $condition2->isSatisfiedBy($order);
        $result3 = $condition3->isSatisfiedBy($order);

        // Assert
        $this->assertTrue($result1);
        $this->assertTrue($result2);
        $this->assertFalse($result3);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnWrightResult_When_ContainsCompare()
    {
        // Arrange
        $order = $this->getOrderWithDifferentProductAttributes();
        $condition1 = new TextAttributeCondition(new TextAttribute('size', 'XXL'), TextAttributesComparator::CONTAINS);
        $condition2 = new TextAttributeCondition(new TextAttribute('size', 'AXXLAb'), TextAttributesComparator::CONTAINS);
        $condition3 = new TextAttributeCondition(new TextAttribute('size', 'BXxLl'), TextAttributesComparator::CONTAINS);

        // Act
        $result1 = $condition1->isSatisfiedBy($order);
        $result2 = $condition2->isSatisfiedBy($order);
        $result3 = $condition3->isSatisfiedBy($order);

        // Assert
        $this->assertTrue($result1);
        $this->assertFalse($result2);
        $this->assertFalse($result3);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnWrightResult_When_ContainsCaseInsensitiveCompare()
    {
        // Arrange
        $order = $this->getOrderWithDifferentProductAttributes();
        $condition1 = new TextAttributeCondition(new TextAttribute('size', 'XXL'), TextAttributesComparator::CONTAINS_CASE_INSENSITIVE);
        $condition2 = new TextAttributeCondition(new TextAttribute('size', 'AXXLAb'), TextAttributesComparator::CONTAINS_CASE_INSENSITIVE);
        $condition3 = new TextAttributeCondition(new TextAttribute('size', 'BXxLl'), TextAttributesComparator::CONTAINS_CASE_INSENSITIVE);
        $condition4 = new TextAttributeCondition(new TextAttribute('size', 'AABXX'), TextAttributesComparator::CONTAINS_CASE_INSENSITIVE);

        // Act
        $result1 = $condition1->isSatisfiedBy($order);
        $result2 = $condition2->isSatisfiedBy($order);
        $result3 = $condition3->isSatisfiedBy($order);
        $result4 = $condition4->isSatisfiedBy($order);

        // Assert
        $this->assertTrue($result1);
        $this->assertFalse($result2);
        $this->assertFalse($result3);
        $this->assertFalse($result4);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnFalse_When_CompareDifferentTypeOfAttributeWithSameName()
    {
        // Arrange
        $order = $this->getOrderWithDifferentProductAttributes();
        $condition = new ListAttributeCondition(new ListAttribute('size', [1, 2]));

        // Act
        $result = $condition->isSatisfiedBy($order);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnFalse_When_AttributeNotFoundInNotEmptyOrder()
    {
        // Arrange
        $order = $this->getOrderWithDifferentProductAttributes();
        $condition = new TextAttributeCondition(new TextAttribute('make', 'abc'));

        // Act
        $result = $condition->isSatisfiedBy($order);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function Should_ReturnFalse_When_EmptyOrder()
    {
        // Arrange
        $order = new Order();
        $condition = new TextAttributeCondition(new TextAttribute('make', 'abc'));

        // Act
        $result = $condition->isSatisfiedBy($order);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return Order
     * @throws InvalidPriceValueException
     */
    private function getOrderWithDifferentProductAttributes(): Order
    {
        $order = new Order();

        $sizeAttribute = new TextAttribute('size', 'XXL');
        $totalAttribute = new NumericAttribute('total', 12345);
        $categoriesAttribute = new ListAttribute('categories', [1, 2, 3, 4, 5]);

        $order->addProduct(new Product('1', 2000));
        $order->addProduct(new Product('2', 1000, [
            $sizeAttribute,
            $totalAttribute,
            $categoriesAttribute
        ]));

        return $order;
    }
}