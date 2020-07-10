<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Condition;

use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\EmptyCondition;
use PHPUnit\Framework\TestCase;

/**
 * Class EmptyConditionTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Condition
 */
class EmptyConditionTest extends TestCase
{
    /**
     * @test
     */
    public function Should_ReturnTrue()
    {
        // Arrange
        $order = $this->createStub(Order::class);
        $condition = new EmptyCondition();

        // Act
        $result = $condition->isSatisfiedBy($order);

        // Assert
        $this->assertTrue($result);
    }
}