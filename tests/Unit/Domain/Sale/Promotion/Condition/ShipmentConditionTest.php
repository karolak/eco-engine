<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Condition;

use Karolak\EcoEngine\Domain\Common\ValueObject\Country;
use Karolak\EcoEngine\Domain\Common\ValueObject\HomeAddress;
use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Shipment;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\ShipmentCondition;
use PHPUnit\Framework\TestCase;

/**
 * Class ShipmentConditionTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Condition
 */
class ShipmentConditionTest extends TestCase
{
    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnTrue_When_PassCondition()
    {
        // Arrange
        $order = new Order();
        $order->setShipment(new Shipment('courier', 1000, new HomeAddress(
            new Country('PL', 'Polska'),
            'test',
            'test',
            'test',
            'test',
            'test',
            'test'
        )));
        $condition = new ShipmentCondition('courier');

        // Act
        $result = $condition->isSatisfiedBy($order);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnFalse_When_NotPassCondition()
    {
        // Arrange
        $order = new Order();
        $order->setShipment(new Shipment('courier', 1000, new HomeAddress(
            new Country('PL', 'Polska'),
            'test',
            'test',
            'test',
            'test',
            'test',
            'test'
        )));
        $condition = new ShipmentCondition('point');

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
        $condition = new ShipmentCondition('point');

        // Act
        $result = $condition->isSatisfiedBy($order);

        // Assert
        $this->assertFalse($result);
    }
}