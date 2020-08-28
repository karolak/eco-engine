<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Condition;

use Karolak\EcoEngine\Domain\Common\Comparator\NumericAttributesComparator;
use Karolak\EcoEngine\Domain\Common\ValueObject\ListAttribute;
use Karolak\EcoEngine\Domain\Common\ValueObject\NumericAttribute;
use Karolak\EcoEngine\Domain\Common\ValueObject\TextAttribute;
use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Product;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\NumericAttributeCondition;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\TextAttributeCondition;
use PHPUnit\Framework\TestCase;

/**
 * Class NumericAttributeConditionTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Condition
 */
class NumericAttributeConditionTest extends TestCase
{
    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnWrightResult_When_EqualsCompare()
    {
        // Arrange
        $order = $this->getOrderWithDifferentProductAttributes();
        $condition1 = new NumericAttributeCondition(new NumericAttribute('total', 12345), NumericAttributesComparator::EQUALS);
        $condition2 = new NumericAttributeCondition(new NumericAttribute('total', 12344), NumericAttributesComparator::EQUALS);

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
    public function Should_ReturnWrightResult_When_EqualsOrHigherCompare()
    {
        // Arrange
        $order = $this->getOrderWithDifferentProductAttributes();
        $condition1 = new NumericAttributeCondition(new NumericAttribute('total', 12345), NumericAttributesComparator::EQUALS_OR_HIGHER);
        $condition2 = new NumericAttributeCondition(new NumericAttribute('total', 12344), NumericAttributesComparator::EQUALS_OR_HIGHER);
        $condition3 = new NumericAttributeCondition(new NumericAttribute('total', 12346), NumericAttributesComparator::EQUALS_OR_HIGHER);

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
    public function Should_ReturnWrightResult_When_EqualsOrLowerCompare()
    {
        // Arrange
        $order = $this->getOrderWithDifferentProductAttributes();
        $condition1 = new NumericAttributeCondition(new NumericAttribute('total', 12345), NumericAttributesComparator::EQUALS_OR_LOWER);
        $condition2 = new NumericAttributeCondition(new NumericAttribute('total', 12344), NumericAttributesComparator::EQUALS_OR_LOWER);
        $condition3 = new NumericAttributeCondition(new NumericAttribute('total', 12346), NumericAttributesComparator::EQUALS_OR_LOWER);

        // Act
        $result1 = $condition1->isSatisfiedBy($order);
        $result2 = $condition2->isSatisfiedBy($order);
        $result3 = $condition3->isSatisfiedBy($order);

        // Assert
        $this->assertTrue($result1);
        $this->assertFalse($result2);
        $this->assertTrue($result3);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnWrightResult_When_HigherCompare()
    {
        // Arrange
        $order = $this->getOrderWithDifferentProductAttributes();
        $condition1 = new NumericAttributeCondition(new NumericAttribute('total', 12345), NumericAttributesComparator::HIGHER);
        $condition2 = new NumericAttributeCondition(new NumericAttribute('total', 12344), NumericAttributesComparator::HIGHER);
        $condition3 = new NumericAttributeCondition(new NumericAttribute('total', 12346), NumericAttributesComparator::HIGHER);

        // Act
        $result1 = $condition1->isSatisfiedBy($order);
        $result2 = $condition2->isSatisfiedBy($order);
        $result3 = $condition3->isSatisfiedBy($order);

        // Assert
        $this->assertFalse($result1);
        $this->assertTrue($result2);
        $this->assertFalse($result3);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnWrightResult_When_LowerCompare()
    {
        // Arrange
        $order = $this->getOrderWithDifferentProductAttributes();
        $condition1 = new NumericAttributeCondition(new NumericAttribute('total', 12345), NumericAttributesComparator::LOWER);
        $condition2 = new NumericAttributeCondition(new NumericAttribute('total', 12344), NumericAttributesComparator::LOWER);
        $condition3 = new NumericAttributeCondition(new NumericAttribute('total', 12346), NumericAttributesComparator::LOWER);

        // Act
        $result1 = $condition1->isSatisfiedBy($order);
        $result2 = $condition2->isSatisfiedBy($order);
        $result3 = $condition3->isSatisfiedBy($order);

        // Assert
        $this->assertFalse($result1);
        $this->assertFalse($result2);
        $this->assertTrue($result3);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnFalse_When_CompareDifferentTypeOfAttributeWithSameName()
    {
        // Arrange
        $order = $this->getOrderWithDifferentProductAttributes();
        $condition = new TextAttributeCondition(new TextAttribute('total', '12345'));

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
        $condition = new NumericAttributeCondition(new NumericAttribute('make', 2331));

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
        $condition = new NumericAttributeCondition(new NumericAttribute('make', 2331));

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