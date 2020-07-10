<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Condition;

use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;

/**
 * Class EmptyCondition
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Condition
 */
class EmptyCondition implements ConditionInterface
{
    /**
     * @param Order $order
     * @return bool
     */
    public function isSatisfiedBy(Order $order): bool
    {
        return true;
    }
}