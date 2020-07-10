<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Condition;

use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;

/**
 * Interface ConditionInterface
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Condition
 */
interface ConditionInterface
{
    /**
     * @param Order $order
     * @return bool
     */
    public function isSatisfiedBy(Order $order): bool;
}