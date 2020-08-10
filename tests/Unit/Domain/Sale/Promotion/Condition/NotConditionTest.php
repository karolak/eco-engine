<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Condition;

use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\EmptyCondition;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\NotCondition;
use PHPUnit\Framework\TestCase;

/**
 * Class NotConditionTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Condition
 */
class NotConditionTest extends TestCase
{
    /**
     * @test
     */
    public function Should_ReturnOpposite_When_UsedOnce()
    {
        // Arrange
        $order = $this->createStub(Order::class);
        $trueCondition = new EmptyCondition();
        $condition = new NotCondition($trueCondition);

        // Act
        $result = $condition->isSatisfiedBy($order);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function Should_ReturnSame_When_UsedTwice()
    {
        // Arrange
        $order = $this->createStub(Order::class);
        $trueCondition = new EmptyCondition();
        $condition = new NotCondition(new NotCondition($trueCondition));

        // Act
        $result = $condition->isSatisfiedBy($order);

        // Assert
        $this->assertTrue($result);
    }
}