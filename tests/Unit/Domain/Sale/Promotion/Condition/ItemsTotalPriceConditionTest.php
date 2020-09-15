<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Condition;

use Karolak\EcoEngine\Domain\Common\Comparator\NumericAttributesComparator;
use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\ItemsTotalPriceCondition;
use PHPUnit\Framework\TestCase;

/**
 * Class ItemsTotalPriceConditionTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Condition
 */
class ItemsTotalPriceConditionTest extends TestCase
{
    /**
     * @test
     * @param int $price
     * @dataProvider trueDataProvider
     */
    public function Should_ReturnTrue(int $price)
    {
        // Arrange
        $order = $this->createMock(Order::class);
        $order->expects($this->any())
            ->method('getTotalProductsPrice')
            ->willReturn($price);
        $condition = new ItemsTotalPriceCondition(1000, NumericAttributesComparator::EQUALS_OR_HIGHER);

        // Act
        $result = $condition->isSatisfiedBy($order);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @test
     * @param int $price
     * @dataProvider falseDataProvider
     */
    public function Should_ReturnFalse(int $price)
    {
        // Arrange
        $order = $this->createMock(Order::class);
        $order->expects($this->any())
            ->method('getTotalProductsPrice')
            ->willReturn($price);
        $condition = new ItemsTotalPriceCondition(1000, NumericAttributesComparator::EQUALS_OR_HIGHER);

        // Act
        $result = $condition->isSatisfiedBy($order);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return \int[][]
     */
    public function trueDataProvider()
    {
        return [
            [1000],
            [2000],
            [2500]
        ];
    }

    /**
     * @return \int[][]
     */
    public function falseDataProvider()
    {
        return [
            [200],
            [999],
            [0]
        ];
    }
}