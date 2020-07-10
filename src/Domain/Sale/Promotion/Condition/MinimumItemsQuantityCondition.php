<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Condition;

use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;

/**
 * Class MinimumItemsQuantityCondition
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Condition
 */
class MinimumItemsQuantityCondition implements ConditionInterface
{
    /** @var int */
    private $quantity;

    /**
     * MinimumItemsQuantityCondition constructor.
     * @param int $quantity
     */
    public function __construct(int $quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @param Order $order
     * @return bool
     */
    public function isSatisfiedBy(Order $order): bool
    {
        return $order->getTotalProductsQuantity() >= $this->quantity;
    }
}