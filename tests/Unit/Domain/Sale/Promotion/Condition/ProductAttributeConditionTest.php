<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Condition;

use Karolak\EcoEngine\Domain\Common\ValueObject\ListAttribute;
use Karolak\EcoEngine\Domain\Common\ValueObject\NumericAttribute;
use Karolak\EcoEngine\Domain\Common\ValueObject\TextAttribute;
use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Product;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\ProductAttributeCondition;
use PHPUnit\Framework\TestCase;

/**
 * Class ProductAttributeConditionTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Condition
 */
class ProductAttributeConditionTest extends TestCase
{
    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnTrue_When_StrictCompare()
    {
        // Arrange
        $order = $this->getOrderWithDifferentProductAttributes();
        $condition1 = new ProductAttributeCondition(new TextAttribute('size', 'XXL'), true);
        $condition2 = new ProductAttributeCondition(new NumericAttribute('total', 12345), true);
        $condition3 = new ProductAttributeCondition(new ListAttribute('categories', [1, 2, 3, 4, 5]), true);

        // Act
        $result1 = $condition1->isSatisfiedBy($order);
        $result2 = $condition2->isSatisfiedBy($order);
        $result3 = $condition3->isSatisfiedBy($order);

        // Assert
        $this->assertTrue($result1);
        $this->assertTrue($result2);
        $this->assertTrue($result3);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnTrue_When_NotStrictCompareTextAttribute()
    {
        // Arrange
        $order = $this->getOrderWithDifferentProductAttributes();
        $condition = new ProductAttributeCondition(new TextAttribute('size', 'L'), false);

        // Act
        $result = $condition->isSatisfiedBy($order);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnTrue_When_NotStrictCompareNumericAttribute()
    {
        // Arrange
        $order = $this->getOrderWithDifferentProductAttributes();
        $condition = new ProductAttributeCondition(new NumericAttribute('total', 12345), false);

        // Act
        $result = $condition->isSatisfiedBy($order);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnTrue_When_NotStrictCompareListAttribute()
    {
        // Arrange
        $order = $this->getOrderWithDifferentProductAttributes();
        $condition = new ProductAttributeCondition(new ListAttribute('categories', [2,3]), false);

        // Act
        $result = $condition->isSatisfiedBy($order);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnFalse_When_CompareDifferentTypeOfAttribute()
    {
        // Arrange
        $order = $this->getOrderWithDifferentProductAttributes();
        $condition = new ProductAttributeCondition(new NumericAttribute('size', 123), false);

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
        $condition = new ProductAttributeCondition(new TextAttribute('make', 'abc'));

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
        $madeAttribute = new TextAttribute('make', 'abc');
        $condition = new ProductAttributeCondition($madeAttribute);

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