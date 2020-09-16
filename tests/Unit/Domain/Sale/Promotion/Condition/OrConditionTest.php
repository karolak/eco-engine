<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Condition;

use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\AndCondition;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\EmptyCondition;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\OrCondition;
use PHPUnit\Framework\TestCase;

/**
 * Class OrConditionTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Condition
 */
class OrConditionTest extends TestCase
{
    /**
     * @test
     */
    public function Should_ReturnFalse_When_AllConditionsAreFalse()
    {
        // Arrange
        $order = $this->createStub(Order::class);
        $condition1 = $this->createMock(EmptyCondition::class);
        $condition1->expects($this->once())
            ->method('isSatisfiedBy')
            ->willReturn(false);
        $condition2 = $this->createMock(EmptyCondition::class);
        $condition2->expects($this->once())
            ->method('isSatisfiedBy')
            ->willReturn(false);
        $condition = new OrCondition($condition1, $condition2);

        // Act
        $result = $condition->isSatisfiedBy($order);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function Should_ReturnTrue_When_MinimumOneConditionIsTrue()
    {
        // Arrange
        $order = $this->createStub(Order::class);
        $condition1 = $this->createMock(EmptyCondition::class);
        $condition1->expects($this->once())
            ->method('isSatisfiedBy')
            ->willReturn(false);
        $condition2 = new EmptyCondition();
        $condition = new OrCondition($condition1, $condition2);

        // Act
        $result = $condition->isSatisfiedBy($order);

        // Assert
        $this->assertTrue($result);
    }
}