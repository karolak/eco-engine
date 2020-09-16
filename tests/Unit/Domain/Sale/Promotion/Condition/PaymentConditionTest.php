<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Condition;

use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Payment;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\PaymentCondition;
use PHPUnit\Framework\TestCase;

/**
 * Class PaymentConditionTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Condition
 */
class PaymentConditionTest extends TestCase
{
    /**
     * @test
     */
    public function Should_ReturnTrue_When_PassCondition()
    {
        // Arrange
        $order = new Order();
        $order->setPayment(new Payment('cod'));
        $condition = new PaymentCondition('cod');

        // Act
        $result = $condition->isSatisfiedBy($order);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function Should_ReturnFalse_When_NotPassCondition()
    {
        // Arrange
        $order = new Order();
        $order->setPayment(new Payment('online'));
        $condition = new PaymentCondition('cod');

        // Act
        $result = $condition->isSatisfiedBy($order);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function Should_ReturnFalse_When_PaymentNotSet()
    {
        // Arrange
        $order = new Order();
        $condition = new PaymentCondition('cod');

        // Act
        $result = $condition->isSatisfiedBy($order);

        // Assert
        $this->assertFalse($result);
    }
}