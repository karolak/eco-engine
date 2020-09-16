<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Condition;

use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;

/**
 * Class ShipmentCondition
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Condition
 */
class ShipmentCondition implements ConditionInterface
{
    /** @var string */
    private $code;

    /**
     * ShipmentCondition constructor.
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * @param Order $order
     * @return bool
     */
    public function isSatisfiedBy(Order $order): bool
    {
        $shipment = $order->getShipment();
        if (null === $shipment) {
            return false;
        }

        return $shipment->getCode() === $this->code;
    }
}