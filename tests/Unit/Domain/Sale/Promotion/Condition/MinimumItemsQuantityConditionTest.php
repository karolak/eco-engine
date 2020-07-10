<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Condition;

use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\MinimumItemsQuantityCondition;
use PHPUnit\Framework\TestCase;

/**
 * Class MinimumItemsQuantityConditionTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Condition
 */
class MinimumItemsQuantityConditionTest extends TestCase
{
    /**
     * @test
     * @param int $quantity
     * @dataProvider trueDataProvider
     */
    public function Should_ReturnTrue(int $quantity)
    {
        // Arrange
        $order = $this->createMock(Order::class);
        $order->expects($this->any())
            ->method('getTotalProductsQuantity')
            ->willReturn($quantity);
        $condition = new MinimumItemsQuantityCondition(3);

        // Act
        $result = $condition->isSatisfiedBy($order);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @test
     * @param int $quantity
     * @dataProvider falseDataProvider
     */
    public function Should_ReturnFalse(int $quantity)
    {
        // Arrange
        $order = $this->createMock(Order::class);
        $order->expects($this->any())
            ->method('getTotalProductsQuantity')
            ->willReturn($quantity);
        $condition = new MinimumItemsQuantityCondition(3);

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
            [3],
            [4],
            [5]
        ];
    }

    /**
     * @return \int[][]
     */
    public function falseDataProvider()
    {
        return [
            [2],
            [1],
            [0]
        ];
    }
}