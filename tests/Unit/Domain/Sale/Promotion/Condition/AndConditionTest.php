<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Condition;

use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\AndCondition;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\EmptyCondition;
use PHPUnit\Framework\TestCase;

/**
 * Class AndConditionTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Condition
 */
class AndConditionTest extends TestCase
{
    /**
     * @test
     */
    public function Should_ReturnTrue_When_AllConditionsAreTrue()
    {
        // Arrange
        $order = $this->createStub(Order::class);
        $condition1 = new EmptyCondition();
        $condition2 = new EmptyCondition();
        $condition = new AndCondition($condition1, $condition2);

        // Act
        $result = $condition->isSatisfiedBy($order);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function Should_ReturnFalse_When_MinimumOneConditionIsFalse()
    {
        // Arrange
        $order = $this->createStub(Order::class);
        $condition1 = $this->createMock(EmptyCondition::class);
        $condition1->expects($this->once())
            ->method('isSatisfiedBy')
            ->willReturn(false);
        $condition2 = new EmptyCondition();
        $condition = new AndCondition($condition1, $condition2);

        // Act
        $result = $condition->isSatisfiedBy($order);

        // Assert
        $this->assertFalse($result);
    }
}