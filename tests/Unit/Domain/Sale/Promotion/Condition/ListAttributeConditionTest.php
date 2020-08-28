<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Condition;

use Karolak\EcoEngine\Domain\Common\Comparator\ListAttributesComparator;
use Karolak\EcoEngine\Domain\Common\ValueObject\ListAttribute;
use Karolak\EcoEngine\Domain\Common\ValueObject\NumericAttribute;
use Karolak\EcoEngine\Domain\Common\ValueObject\TextAttribute;
use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Product;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\ListAttributeCondition;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\NumericAttributeCondition;
use PHPUnit\Framework\TestCase;

/**
 * Class ListAttributeConditionTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Condition
 */
class ListAttributeConditionTest extends TestCase
{
    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnWrightResult_When_StrictCompare()
    {
        // Arrange
        $order = $this->getOrderWithDifferentProductAttributes();
        $condition1 = new ListAttributeCondition(new ListAttribute('categories', [1, 2, 3, 4, 5]), ListAttributesComparator::STRICT);
        $condition2 = new ListAttributeCondition(new ListAttribute('categories', [1, 2, 3, 5, 4]), ListAttributesComparator::STRICT);
        $condition3 = new ListAttributeCondition(new ListAttribute('categories', [1, 2, 3, 5, 6]), ListAttributesComparator::STRICT);

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
    public function Should_ReturnWrightResult_When_InCompare()
    {
        // Arrange
        $order = $this->getOrderWithDifferentProductAttributes();
        $condition1 = new ListAttributeCondition(new ListAttribute('categories', [1, 2, 3, 4, 5]), ListAttributesComparator::IN);
        $condition2 = new ListAttributeCondition(new ListAttribute('categories', [1, 2, 3, 5, 4]), ListAttributesComparator::IN);
        $condition3 = new ListAttributeCondition(new ListAttribute('categories', [1, 2, 3, 5, 6]), ListAttributesComparator::IN);
        $condition4 = new ListAttributeCondition(new ListAttribute('categories', [11, 22, 33, 55, 66]), ListAttributesComparator::IN);

        // Act
        $result1 = $condition1->isSatisfiedBy($order);
        $result2 = $condition2->isSatisfiedBy($order);
        $result3 = $condition3->isSatisfiedBy($order);
        $result4 = $condition4->isSatisfiedBy($order);

        // Assert
        $this->assertTrue($result1);
        $this->assertTrue($result2);
        $this->assertTrue($result3);
        $this->assertFalse($result4);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnFalse_When_CompareDifferentTypeOfAttribute()
    {
        // Arrange
        $order = $this->getOrderWithDifferentProductAttributes();
        $condition = new NumericAttributeCondition(new NumericAttribute('categories', 123));

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
        $condition = new ListAttributeCondition(new ListAttribute('make', [1, 2]));

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
        $condition = new ListAttributeCondition(new ListAttribute('make', [1, 2]));

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